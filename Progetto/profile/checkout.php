<link rel="stylesheet" href="./asset/styles/checkout.css" type="text/css">

<script type="text/javascript">
    var TotaleProd = parseFloat($("#totalProd").text());
    var IDCorriere = -1;

    $(document).ready(function() {
        $('input[type="radio"][name="express"]').click(function() {
            var Costo = parseFloat($('input[type="radio"][name="express"]:checked').val());

            $("#costoSped").text(String(Costo));

            var Totale = TotaleProd + Costo;

            $("#totale").text(String(Totale));
        });
    });

    $(document).ready(function() {
        $('input[type="radio"][name="express"]').click();
    });

    $(document).ready(function() {
        var CostoSped = parseFloat($("#costoSped").text());

        var Totale = TotaleProd + CostoSped;

        $("#totale").text(String(Totale));
    });

    function setCorriere(IDCorr)
    {
        IDCorriere = IDCorr;
    }

    function confirm()
    {
        confirmCheckout(IDCorriere);
    }
</script>

<?php
include("../include/BasicLib.php");

/* Effettuo qua tutti i vari calcoli riguardo al totale che bisogna pagare, contando sconti etc.*/

//Access to JSON of the Cart. JSON is best way to keep data of my cart!
$cartInfo = json_decode($_SESSION['cart'], true);

$Totale = 0;

for($i = 0; $i < count($cartInfo['cart']); $i++) {

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
    }
}
?>

<div id="checkoutPage">
    <div class="title">
        Procedi con l'acquisto
    </div>
    <div class="back">
        <button class="backButtonStyle" onclick="myCart()">Indietro</button>
    </div>

    <!-- Questo mi imposterà per bene la round box attorno ai float! -->
    <div style="clear: both;"></div>

    <?php
    $SQL = "SELECT *
            FROM utenti
            WHERE ID='$idSession'";

    $result = $conn->query($SQL);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $Nome = $row['Nome'];
        $Cognome = $row['Cognome'];
        $Citta = $row['Citta'];
        $CAP = $row['CAP'];
        $Indirizzo = $row['Indirizzo'];
        $Num_Tel = $row['Num_Tel'];
        $Cod_Fisc = $row['Cod_Fisc'];
        $email = $row['Email'];
    }
    ?>

    <div class="infoPurchase">
        <div class="infoInvoice">
            <?php
            echo "Nome: $Nome $Cognome<br>";
            echo "Codice Fiscale: $Cod_Fisc<br>";
            echo "Indirizzo: $Indirizzo<br>";
            echo "$Citta, $CAP<br>";
            echo "Telefono: $Num_Tel<br>";
            echo "Email: $email<br>";
            ?>
            <br><br>
            <table>
                <th></th>
                <th>Nome</th>
                <th>Costo</th>
                <th>Tempo Consegna</th>
            <?php
            $SQL = "SELECT *
                    FROM corrieri";

            $result = $conn->query($SQL);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $ID = $row['ID'];
                    $Nominativo = $row['Nominativo'];
                    $Costo = $row['Costo'];
                    $Tempo = $row['Tempi_Consegna'];

                    echo "<tr>";
                    echo "<td><input type='radio' name='express' value='$Costo' onclick='setCorriere($ID)'></td>";
                    echo "<td>$Nominativo</td>";
                    echo "<td>$Costo €</td>";
                    echo "<td>$Tempo<br></td>";
                    echo "</tr>";
                }
            }
            ?>
            </table>
        </div>
    </div>
    <div class="summary">
        Totale Prodotti:<br>
        <?php echo "<span id=\"totalProd\">".$Totale."</span> Euro"; ?><br><br>
        Costo spedizioni<br>
        <?php echo "<span id=\"costoSped\">0</span> Euro"; ?><br>
        <hr>
        Totale da pagare<br>
        <?php echo "<span id=\"totale\">0</span> Euro<br><br>"; ?>

        <button onclick="confirm()">Conferma Pagamento</button>
    </div>

    <!-- Questo mi imposterà per bene la round box attorno ai float! -->
    <div style="clear: both;"></div>
</div>
<?php
$conn->close();
?>