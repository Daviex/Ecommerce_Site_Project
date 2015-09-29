<link rel="stylesheet" href="./asset/styles/confirmCheckout.css" type="text/css">

<div id="confirmcheckoutPage">
    <div class="title">
        Conferma checkout
    </div>
<?php
include("../include/BasicLib.php");

/* Effettuo qua tutti i vari calcoli riguardo al totale che bisogna pagare, contando sconti etc.*/

if(isset($_GET['IDCorr'])) {
    $IDCorr = $_GET['IDCorr'];
    //Access to JSON of the Cart. JSON is best way to keep data of my cart!
    $cartInfo = json_decode($_SESSION['cart'], true);

    $SQL = "SELECT *
            FROM corrieri
            WHERE ID = '$IDCorr'";

    $result = $conn->query($SQL);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $Totale = $row['Costo'];

        $SQL = "SELECT *
            FROM utenti
            WHERE ID = '$idSession'";

        $result = $conn->query($SQL);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $CittaArr = $row['Citta'];
            $IndirizzoArr = $row['Indirizzo'];
            $CAPArr = $row['CAP'];

            $SQL = "INSERT INTO spedizioni (Luogo_Part, Citta_Arr, Indirizzo_Arr, CAP_Arr, IDCorr) VALUES ('Messina', '".$conn->real_escape_string($CittaArr)."', '".$conn->real_escape_string($IndirizzoArr)."', '".$conn->real_escape_string($CAPArr)."', '$IDCorr')";

            $conn->query($SQL);

            $IDSped = $conn->insert_id;

            $Data = date("Y-m-d H:i:s");

            $conn->query("INSERT INTO ordini (ID, Data_Acq, IDUser) VALUES ('', '$Data','$idSession')");

            $IDOrdine = $conn->insert_id;

            for ($i = 0; $i < count($cartInfo['cart']); $i++) {

                $productID = $cartInfo['cart'][$i]['IDProd'];
                $quantita = $cartInfo['cart'][$i]['Quantita'];

                $SQL = "SELECT prod.ID AS IDProd, prod.Nome AS ProdNome, prod.Prezzo AS Prezzo, prod.Quantita AS Quantita, sold.Percentuale AS Sconto
                    FROM prodotti AS prod LEFT JOIN sconti AS sold ON prod.ID = sold.IDProd
                    WHERE prod.ID = '$productID'";

                $result = $conn->query($SQL);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();

                    $IDProd = $row['IDProd'];
                    $ProdName = $row['ProdNome'];

                    $Prezzo = (float)$row['Prezzo'];
                    $QuantitaDisponibile = (int)$row['Quantita'];

                    $discount = 0;
                    if (isset($row['Sconto']))
                        $discount = $row['Sconto'];

                    if ($quantita > $QuantitaDisponibile)
                        $quantita = $QuantitaDisponibile;
                    elseif ($quantita <= 0)
                        $quantita = 1;

                    $TotaleCorrente = $Prezzo * $quantita;

                    if ($discount > 0)
                        $TotaleCorrente = round($TotaleCorrente - (($TotaleCorrente * $discount) / 100), 2);


                    $Totale += $TotaleCorrente;

                    $SQL = "INSERT INTO acquisti (Costo, Quantita, IDProd, IDSped, IDStato, IDOrdine) VALUES ('$TotaleCorrente', '$quantita', '$productID', '$IDSped','2', '$IDOrdine')";

                    $conn->query($SQL) or die ($conn->error);

                    $SQL = "UPDATE prodotti SET Quantita = Quantita - $quantita WHERE ID = '$productID'";

                    $conn->query($SQL) or die ($conn->error);

                }
                else {
                    echo "Errore nel checkout! Ritorno alla homepage...";
                    ?>
                    <script type="text/javascript">
                        setTimeout(function () {
                            window.location.href = "index.php";
                        }, 1500);
                    </script>
                <?php
                }
            }
            if($result)
            {
                echo "Acquisto effettuato con successo! Grazie per averci scelto!<br>Tieni d'occhio la tua pagina Ordini per sapere in ogni momento<br>dove si trovano i tuoi prodotti!";
                unset($_SESSION['cart']);
                ?>
                <script type="text/javascript">
                    setTimeout(function () {
                        window.location.href = "index.php";
                    }, 1500);
                </script>
                <?php
            }
            else
            {
                echo "Errore nel checkout! Ritorno alla homepage...";
                ?>
                    <script type="text/javascript">
                        setTimeout(function () {
                            window.location.href = "index.php";
                        }, 1500);
                    </script>
                <?php
            }
        }
        else {
                echo "Errore nel checkout! Ritorno alla homepage...";
                ?>
            <script type="text/javascript">
                setTimeout(function () {
                    window.location.href = "index.php";
                }, 1500);
            </script>
        <?php
        }
    }
}
?>
</div>
<?php
$conn->close();
?>
