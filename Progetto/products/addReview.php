<link rel="stylesheet" href="./asset/styles/addReview.css" type="text/css">

<script type="text/javascript">
    $(document).ready(function(){
        $("#submit").click(function(){
            var Titolo = $("#reviewTitle").val();
            var Testo = $("#reviewText").val();
            var Voto = $("#reviewVote").val();
            var IDAcq = $("#reviewPurchase").val();
            var IDProd = $("#reviewProduct").val();

            $.post(
                "products/addReview.php",
                {
                    IDAcq: IDAcq,
                    IDProd: IDProd,
                    Titolo: Titolo,
                    Testo: Testo,
                    Voto: Voto
                },
                function(msg)
                {
                    loadPageWithFXAjax(msg);
                });
        });
    });
</script>

<?php
include("../include/BasicLib.php");

if(!checkParameter(@$_POST['IDAcq']) || !checkParameter(@$_POST['IDProd'])|| !checkParameter(@$_POST['Titolo']) || !checkParameter(@$_POST['Testo']) || !checkParameter(@$_POST['Voto']) ) {
    if (isset($_GET['idProd']))
        $productID = $_GET['idProd'];
    else
        $productID = -1;
    ?>

    <div id="addreviewPage">
        <div class="title">
            Nuova Recensione
        </div>

        <?php
        if ($productID != -1) {
        if ($logged) {
            $SQL = "SELECT acquisti.ID
                FROM acquisti JOIN ordini ON acquisti.IDOrdine = ordini.ID
                WHERE IDProd = '$productID' AND IDUser = '$idSession' ORDER BY ID ASC LIMIT 1";

            $result = $conn->query($SQL);

            //Se trovo un acquisto di questo utente su questo prodotto
        if ($result->num_rows > 0) {
            $IDAcq = $result->fetch_assoc()['ID'];

            $SQL = "SELECT *
                    FROM ( recensioni AS rec JOIN acquisti AS acq ON acq.ID = rec.IDAcq ) JOIN ordini ON acq.IDOrdine = ordini.ID
                    WHERE acq.IDProd = '$productID' AND ordini.IDUser = '$idSession' AND acq.ID = '$IDAcq'";

            $result = $conn->query($SQL);

            //Se non trovo recensioni su un determinato acquisto di quell'utente su quel prodotto
        if ($result->num_rows == 0) {
            $SQL = "SELECT prod.Nome AS Nome, prod.IMG AS Image, cat.Nome AS NomeCat
                  FROM prodotti AS prod JOIN categorie AS cat ON prod.IDCat=cat.ID
                  WHERE prod.ID='$productID'";

            $result = $conn->query($SQL);

            //Se trovo il prodotto
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $image = strtolower($row['NomeCat']) . "/" . $row['Image'];
            $nome = $row['Nome'];
            ?>
            <div class="image">
                <img src="products/images/<?php echo $image; ?>" width="120px" height="120px">
            </div>

            <div class="productName">
                <a href="#" onclick="product('<?php echo $productID; ?>')"><?php echo $nome; ?></a><br>
            </div>
        <br>

            <div class="reviewSection">
                <form name="addReview">
                    <div class="title">
                        Titolo recensione:
                    </div>
                    <input type="text" id="reviewTitle" class="reviewTitle">

                    <br>
                    <br>

                    <div class="text">
                        Testo:
                    </div>
                    <textarea class="reviewText" id="reviewText" maxlength="1300"></textarea>

                    <br>
                    <br>

                    <div class="vote">
                        Voto: &nbsp;
                    </div>
                    <select id="reviewVote" class="voteSelect">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>

                    <br>
                    <br>

                    <input type="hidden" id="reviewPurchase" value="<?php echo $IDAcq; ?>">
                    <input type="hidden" id="reviewProduct" value="<?php echo $productID; ?>">

                    <div class="button">
                        <button id="submit">Conferma</button>
                    </div>
                </form>
            </div>

            <!-- Questo mi imposterà per bene la round box attorno ai float! -->
            <div style="clear: both;"></div>
        <?php
        } else {
        echo "Errore! Verrai rimandato alla homepage!";
        ?>
            <script type="text/javascript">
                setTimeout(function () {
                    window.location.href = "index.php";
                }, 1500);
            </script>
        <?php
        }
        } else {
        echo "Errore! Hai già scritto una recensione per questo prodotto! Verrai rimandato alla homepage!";
        ?>
            <script type="text/javascript">
                setTimeout(function () {
                    window.location.href = "index.php";
                }, 1500);
            </script>
        <?php
        }
        } else {
        echo "Errore! Non hai acquistato questo prodotto!";
        ?>
            <script type="text/javascript">
                setTimeout(function () {
                    window.location.href = "index.php";
                }, 1500);
            </script>
        <?php
        }
        } else {
        echo "Effettua prima il login!";
        ?>
            <script type="text/javascript">
                setTimeout(function () {
                    window.location.href = "index.php";
                }, 1500);
            </script>
        <?php
        }
        } else {
        echo "Errore! Verrai rimandato alla homepage!";
        ?>
            <script type="text/javascript">
                setTimeout(function () {
                    window.location.href = "index.php";
                }, 1500);
            </script>
        <?php
        }
        ?>

    </div>
<?php
}
else {
    ?>
    <h1>Nuova Recensione</h1>
    <?php
    $IDAcq = $_POST['IDAcq'];
    $IDProd = $_POST['IDProd'];
    $Titolo = $_POST['Titolo'];
    $Testo = $_POST['Testo'];
    $Data = date("Y-m-d H:i:s");
    $Voto = (int)$_POST['Voto'];

    if (strlen($Titolo) > 60) {
        echo "Errore nell'inserire i dati ( TITOLO )! Verrai rimandato alla homepage!";
        ?>
        <script type="text/javascript">
            setTimeout(function () {
                window.location.href = "index.php";
            }, 1500);
        </script>
    <?php
    }

    if (strlen($Testo) > 1300) {
        echo "Errore nell'inserire i dati ( TESTO )! Verrai rimandato alla homepage!";
        ?>
        <script type="text/javascript">
            setTimeout(function () {
                window.location.href = "index.php";
            }, 1500);
        </script>
    <?php
    }

    if ($Voto > 5 || $Voto < 0) {
        echo "Errore nell'inserire i dati ( VOTO )! Verrai rimandato alla homepage!";
        ?>
        <script type="text/javascript">
            setTimeout(function () {
                window.location.href = "index.php";
            }, 1500);
        </script>
    <?php
    }

    $SQL = "INSERT INTO recensioni (Titolo, Testo, Data, Voto, IDAcq) VALUES (\"" . $conn->real_escape_string($Titolo) . "\", \"" . $conn->real_escape_string($Testo) . "\", '" . $Data . "','" . $Voto . "', '" . $IDAcq . "')";

    $result = $conn->query($SQL) or die ($conn->error);

    if ($result) {
        echo "Recensione aggiunta, verrai rimandato alla pagina del prodotto...";
        ?>
        <script type="text/javascript">
            setTimeout(function () {
                product(<?php echo $IDProd; ?>);
            }, 1500);
        </script>
    <?php
    } else {
        echo "Errore ( SQL )! Verrai rimandato alla homepage!";
        ?>
        <script type="text/javascript">
            setTimeout(function () {
                window.location.href = "index.php";
            }, 1500);
        </script>
    <?php
    }
}
$conn->close();
?>