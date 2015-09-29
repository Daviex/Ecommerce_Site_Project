<link rel="stylesheet" href="./asset/styles/cart.css" type="text/css">

<script type="text/javascript">
    function updateQuantity(index, idProd)
    {
        var quantity = $('input[name^="quantity"]');
        quantity = quantity.eq(index).val();
        var IDProd = idProd;

        updateCart(IDProd, quantity);
    }

    function removeProduct(idProd)
    {
        var IDProd = idProd;

        removeCart(IDProd);
    }
</script>

<?php
include("../include/BasicLib.php");

//Access to JSON of the Cart. JSON is best way to keep data of my cart!
$cartInfo = json_decode($_SESSION['cart'], true);
?>

<div id="cartPage">
    <div class="title">
        Il mio carrello
    </div>
    <div class="back">
        <button class="backButtonStyle" onclick="myProfile()">Indietro</button>
    </div>

    <!-- Questo mi imposterà per bene la round box attorno ai float! -->
    <div style="clear: both;"></div>

    <?php
    $numProdotti = count($cartInfo['cart']);
    $Totale = 0;
    $TotaleRisparmio = 0;
    if($numProdotti > 0) {
        ?>
        <div class="cartInfo">
            <?php
            for ($i = 0; $i < $numProdotti; $i++) {
                $productID = $cartInfo['cart'][$i]['IDProd'];
                $quantita = $cartInfo['cart'][$i]['Quantita'];

                $SQL = "SELECT prod.ID AS IDProd, prod.Nome AS ProdNome, prod.Prezzo AS Prezzo, prod.Quantita AS Quantita, prod.IMG AS Image, cat.Nome AS CatNome, sold.Percentuale AS Sconto
            FROM ( prodotti AS prod JOIN categorie AS cat ON cat.ID = prod.IDCat ) LEFT JOIN sconti AS sold ON prod.ID = sold.IDProd
            WHERE prod.ID = '$productID'";

                $result = $conn->query($SQL);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();

                    $IDProd = $row['IDProd'];
                    $ProdName = $row['ProdNome'];
                    $CatNome = $row['CatNome'];
                    $Prezzo = (float)$row['Prezzo'];
                    $QuantitaDisponibile = (float)$row['Quantita'];
                    $Image = "products/images/" . strtolower($CatNome) . "/" . $row['Image'];

                    $discount = 0;
                    if (isset($row['Sconto']))
                        $discount = $row['Sconto'];

                    if ($quantita > $QuantitaDisponibile)
                        $quantita = $QuantitaDisponibile;
                    elseif ($quantita <= 0)
                        $quantita = 1;

                    $cartInfo['cart'][$i]['Quantita'] = $quantita;

                    $TotaleCorrente = $Prezzo * $quantita;

                    echo "<div class='roundedBox'>";
                    echo "<div class='links'>";
                    echo "<a href='#' onclick='product(\"$IDProd\")'>$ProdName</a>";
                    echo "<button onclick='removeProduct($IDProd)'>X</button>";
                    echo "</div>";

                    echo "<div class=\"tableElements image\">";
                    echo "<img src=\"$Image\" width='120px' height='120px'>";
                    echo "</div>";

                    echo "<div class=\"tableElements Quantity\">";
                    echo "Quantità: <br>";

                    echo "<input type='number' min='1' max='$QuantitaDisponibile' name=\"quantity[]\" value='$quantita' onchange='updateQuantity($i, $IDProd)'> <br>";
                    if ($quantita == $QuantitaDisponibile)
                        echo "<span style='color: red'>Disponibilità<br>Massima<br>Raggiunta</span><br>";
                    echo "</div>";

                    echo "<div class=\"tableElements price\">";
                    echo "Prezzo: <br>";
                    echo "$Prezzo Euro";
                    echo "</div>";

                    echo "<div class=\"tableElements price\">";
                    echo "Totale: <br>";
                    echo $TotaleCorrente . " Euro";
                    echo "</div>";

                    if ($discount > 0) {
                        $Risparmio = round(($TotaleCorrente * $discount) / 100, 2);
                        $TotaleCorrente = round($TotaleCorrente - $Risparmio, 2);
                        $TotaleRisparmio += $Risparmio;
                        echo "<div class=\"tableElements discount\">";
                        echo "Scontato del $discount%: <br>";
                        echo $TotaleCorrente . " Euro";
                        echo "</div>";
                    }
                    $Totale += $TotaleCorrente;

                    echo "<div style=\"clear: both;\"></div>";
                    echo "</div>";
                }
            }
            ?>
            <!-- Questo mi imposterà per bene la round box attorno ai float! -->
            <div style="clear: both;"></div>
        </div>
    <?php
        /* Inizia la parte della parte bassa della pagina dei prodotti che ho acquistato */
        ?>
        <div class="checkoutInfo">
            <div class="checkout">
                <button onclick="checkoutCart()">Checkout</button>
            </div>
            <?php
            if($TotaleRisparmio > 0) {
                ?>
                <div class="saving">
                    Risparmi: <?php echo $TotaleRisparmio; ?> Euro
                </div>
            <?php
            }
            ?>
            <div class="total">
                Totale: <?php echo $Totale; ?> Euro
            </div>
        </div>
        <!-- Questo mi imposterà per bene la round box attorno ai float! -->
        <div style="clear: both;"></div>
        <?php
    }
    else {
        echo "Non hai nessun prodotto nel carrello, torna più tardi!";
    }

    ?>

</div>

<?php
$conn->close();
?>