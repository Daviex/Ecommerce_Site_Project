<link rel="stylesheet" href="./asset/styles/product.css" type="text/css">

<script type="text/javascript">
    $(document).ready(function() {
       $("#addCart").click(function(){
           var IDProd = $("#IDProd").val();
           var Quantita = $("#Quantity").val();

           $.post(
               "products/addCart.php",
               {
                   IDProd : IDProd,
                   Quantita : Quantita
               },
               function(msg) {
                   loadPageWithFXAjax(msg);
               }
           )
       });
    });
</script>

<?php
include("../include/BasicLib.php");

if(isset($_GET['idProd']))
    $productID = $_GET['idProd'];
else
    $productID = -1;

if($productID != -1) {
    $SQL = "SELECT prod.Nome AS Nome, prod.Desc AS Descr, prod.Prezzo AS Prezzo, prod.Quantita AS Quant, prod.IMG AS Image, cat.Nome AS NomeCat, sold.Percentuale AS Sconto
            FROM (prodotti AS prod JOIN categorie AS cat ON prod.IDCat=cat.ID) LEFT JOIN sconti as sold ON sold.IDProd = prod.ID
            WHERE prod.ID='$productID'";
    $result = $conn->query($SQL);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        $image = strtolower($row['NomeCat']) . "/" . $row['Image'];
        $nome = $row['Nome'];
        $desc = $row['Descr'];
        $prezzo = $row['Prezzo'];
        $quantita = $row['Quant'];
        $discount = $row['Sconto'];
        ?>

        <div id="productPage">
        <!--<div class="image">-->
        <img src="products/images/<?php echo $image; ?>">
        <!--</div>-->

        <div class="productName">
            <?php echo $nome; ?>
        </div>

        <br><br><br>

        <div class="infoBought">
            <div class="productQuant">
                <input type="hidden" id="IDProd" value="<?php echo $productID; ?>">
                <p>Quantità:</p><input type="number" id="Quantity" name="Quantity" value="1"
                                       min="1" max="100">
            </div>
            <div class="productPrice">
                <?php
                if($discount <= 0)
                    echo $prezzo." Euro / cad.";
                else
                {
                    echo "<s>".$prezzo." Euro / cad.</s><br>";
                    $Risparmio = round(($prezzo * $discount) / 100, 2);
                    $newPrice = round($prezzo - $Risparmio, 2);
                    echo "<span style='color: red'>".$newPrice." Euro / cad.</span>";
                }
                ?>
            </div>
        </div>


        <br><br><br>

        <div class="productBuy">
            <?php
            if($quantita <= 0) {
                ?>
                <button disabled="true">Prodotto non disponibile</button>
            <?php
            }
            elseif(!$logged) {
                ?>
                <button disabled="true"">Effettua prima il login!</button>
            <?php
            }
            else {
                ?>
                <button id="addCart">Aggiungi al carrello</button>
            <?php
            }
            ?>
        </div>

        <div style="clear: both"></div>

        <br><br><br>

        <div class="description">
            <div class="title">
                Descrizione del prodotto
            </div>
            <div class="productDesc">
                <?php echo $desc; ?>
            </div>
        </div>

        <br><br><br><br>

        <?php

        $SQL = "SELECT AVG(Voto) AS Media, COUNT(*) AS NumVoti FROM recensioni AS rec JOIN acquisti AS acq ON acq.ID = rec.IDAcq WHERE acq.IDProd = '$productID'";

        $result = $conn->query($SQL);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $mediaVoti = (int)($row['Media']);
            $numVoti = (int)($row['NumVoti']);
            ?>

            <div class="reviews">
                <div class="title">
                    Recensioni degli utenti
                </div>
                <div class="averageofVotes">
                    Media Voti: <?php echo $mediaVoti; ?> / 5
                    con <?php if ($numVoti != 1) echo $numVoti . " voti"; else echo $numVoti . " voto"; ?>
                </div>
                <div class="votesClass">
                    <?php
                    $SQL = "SELECT Voto, COUNT(*) AS Voti FROM recensioni AS rec JOIN acquisti AS acq ON acq.ID = rec.IDAcq WHERE acq.IDProd = '$productID' GROUP BY Voto ORDER BY Voto DESC";
                    $result = $conn->query($SQL);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $voti = $row['Voti'];
                            $countVoto = $row['Voto'];
                            echo $countVoto . " - ";
                            echo "<progress value=\"" . $voti . "\" max=\"$numVoti\"></progress>&nbsp;";
                            echo "$voti su $numVoti <br>";
                        }
                    }
                    ?>
                </div>
                <br>

                <div class="userReviews">
                    <!-- Il proprio commento in evidenza -->
                    <?php
                    $userHaveReview = false;
                    $SQL = "SELECT rec.Titolo AS Titolo, rec.Testo AS Testo, rec.Data AS Data, rec.Voto AS Voto, uten.Nome AS NomeUser, uten.Cognome AS CognomeUser
                            FROM (( recensioni AS rec JOIN acquisti AS acq ON acq.ID = rec.IDAcq ) JOIN ordini AS ord ON ord.ID = acq.IDOrdine ) JOIN utenti AS uten ON ord.IDUser = uten.ID
                            WHERE acq.IDProd = '$productID' AND ord.IDUser = '$idSession'";
                    $result = $conn->query($SQL);
                    if ($result->num_rows > 0) {
                        $userHaveReview = true;
                        $row = $result->fetch_assoc();
                        $titoloRec = $row['Titolo'];
                        $testoRec = $row['Testo'];
                        $dataRec = MySqlDateTimeToItalian($row['Data']);
                        $votoRec = $row['Voto'];
                        $nomeUser = $row['NomeUser'];
                        $cognomeUser = $row['CognomeUser'];
                        ?>
                        <div class="subReviews emphatized">
                            <div class="reviewTitle">
                                <span class="title"><?php echo $titoloRec; ?></span>
                                <span class="space"></span>
                                <span class="user"><?php echo "by " . $nomeUser . " " . $cognomeUser[0] . "."; ?></span>
                                <span class="space"></span>
                                <span class="user"><?php echo "il " . $dataRec . ""; ?></span>
                                <span class="space"></span>
                                <span class="remove" onclick="removeReview(<?php echo $productID; ?>)">Rimuovi</span>
                                <span class="vote" style="padding-right: 1%;"><?php echo "Voto: " . $votoRec . "/5"; ?></span>
                            </div>
                            <br>

                            <div class="reviewSection">
                                <!-- 1300 Caratteri Massimo, implementare via software all'inserimento via PHP -->
                                <?php echo $testoRec; ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <!-- Commenti di tutti gli utenti tranne il proprio -->
                    <?php

                    $SQL = "SELECT rec.Titolo AS Titolo, rec.Testo AS Testo, rec.Data AS Data, rec.Voto AS Voto, uten.Nome AS NomeUser, uten.Cognome AS CognomeUser
                            FROM (( recensioni AS rec JOIN acquisti AS acq ON acq.ID = rec.IDAcq ) JOIN ordini AS ord ON ord.ID = acq.IDOrdine ) JOIN utenti AS uten ON ord.IDUser = uten.ID
                                WHERE acq.IDProd = '$productID' AND ord.IDUSer <> '$idSession'
                                ORDER BY rec.Data DESC";
                    $result = $conn->query($SQL);
                    if ($result->num_rows > 0) {
                        if ($userHaveReview)
                            echo "<hr>";
                        while ($row = $result->fetch_assoc()) {
                            $titoloRec = $row['Titolo'];
                            $testoRec = $row['Testo'];
                            $dataRec = MySqlDateTimeToItalian($row['Data']);
                            $votoRec = $row['Voto'];
                            $nomeUser = $row['NomeUser'];
                            $cognomeUser = $row['CognomeUser'];
                            ?>
                            <div class="subReviews">
                                <div class="reviewTitle">
                                    <span class="title"><?php echo $titoloRec; ?></span>
                                    <span class="space"></span>
                                    <span
                                        class="user"><?php echo "by " . $nomeUser . " " . $cognomeUser[0] . "."; ?></span>
                                    <span class="space"></span>
                                    <span class="user"><?php echo "il " . $dataRec . ""; ?></span>
                                    <span class="space"></span>
                                    <span class="vote"><?php echo "Voto: " . $votoRec . "/5"; ?></span>
                                </div>
                                <br>

                                <div class="reviewSection">
                                    <!-- 1300 Caratteri Massimo, implementare via software all'inserimento via PHP -->
                                    <?php echo $testoRec; ?>
                                </div>
                            </div>
                        <?php
                        }
                    } ?>
                </div>
                <br>

                <?php
                if ($logged) {
                    $SQL = "SELECT acq.ID AS IDAcq FROM acquisti AS acq JOIN ordini AS ord ON acq.IDOrdine = ord.ID WHERE IDProd = '$productID' AND IDUser = '$idSession' ORDER BY acq.ID ASC LIMIT 1";
                    $result = $conn->query($SQL);

                    //Se l'utente ha mai comprato quel prodotto
                    if ($result->num_rows > 0) {
                        $IDAcq = $result->fetch_assoc()['IDAcq'];

                        $SQL = "SELECT * FROM acquisti AS acq JOIN recensioni AS rec ON acq.ID = rec.IDAcq WHERE acq.ID = '" . $IDAcq . "'";
                        $result = $conn->query($SQL);

                        //Se l'utente non ha mai recensito quel prodotto
                        if ($result->num_rows == 0) {
                            ?>
                            <div class="newReview">
                                <button onclick="addReview(<?php echo "'" . $productID . "'"; ?>)">
                                    Inserisci una recensione
                                </button>
                            </div>
                        <?php
                        } else {
                            ?>
                            <div class="newReview">
                                <button disabled="true">Hai già inserito una recensione</button>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <div class="newReview">
                            <button disabled="true">Prima acquista il prodotto</button>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <div class="newReview">
                        <button disabled="true">Devi prima effettuare il log in</button>
                    </div>
                <?php
                }
                ?>
            </div>

            <div style="clear: both"></div>
            </div>
        <?php
        }
    } else {
        echo "Errore 2! Verrai rimandato alla homepage!";
        ?>
        <script type="text/javascript">
            setTimeout(function () {
                window.location.href = "index.php";
            }, 1500);
        </script>
    <?php
    }
}
else
{
    echo "Errore 1! Verrai rimandato alla homepage!";
    ?>
    <script type="text/javascript">
        setTimeout(function(){ window.location.href = "index.php"; }, 1500);
    </script>
    <?php
}
$conn->close();
?>