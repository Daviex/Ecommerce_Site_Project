<link rel="stylesheet" href="./asset/styles/search.css" type="text/css">

<?php
include("include/BasicLib.php");

if(isset($_POST['SearchText']))
    $SearchText = $_POST['SearchText'];
else
    $SearchText = null;
?>

<div id="searchPage">
    <div class="title">
        Risultati ricerca effettuata
    </div>

    <?php
    $SQL = "SELECT
      cat.Nome AS catNome,
      prod.ID AS prodID,
      prod.Nome AS prodNome,
      prod.Desc AS prodDesc,
      prod.Prezzo AS prodPrice,
      prod.IMG AS prodIMG
    FROM ( categorie AS cat RIGHT JOIN prodotti as prod ON cat.ID = prod.IDCat )
    WHERE prod.Nome LIKE '%$SearchText%' OR prod.Desc LIKE '%$SearchText%' OR cat.Nome LIKE '%$SearchText'";

    $result = $conn->query($SQL);

    if($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $catNome = $row['catNome'];
            $prodID = $row['prodID'];
            $prodName = $row['prodNome'];
            $prodDesc = $row['prodDesc'];
            $prodPrice = $row['prodPrice'];
            $prodIMG = $row['prodIMG'];
            ?>
            <div class="separator">
                <div class="image">
                    <?php
                    echo "<img src=\"products/images/" . strtolower($catNome) . "/" . $prodIMG . "\" width=\"120px\" height=\"120px\">";
                    ?>
                </div>
                <div class="productName">
                    <?php
                    echo "<a href=\"#\" onclick=\"product(" . $prodID . ")\">" . $prodName . "</a><br>";
                    ?>
                </div>
                <div class="infoProduct">
                    <?php
                    if (strlen($prodDesc) >= 378)
                        $prodDesc = substr($prodDesc, 0, -3) . "...";
                    echo "$prodDesc";
                    ?>
                </div>

                <!-- Questo mi imposterà per bene la round box attorno ai float! -->
                <div style="clear: both;"></div>
            </div>
            <br>
        <?php
        }
    }
    ?>

    <!-- Questo mi imposterà per bene la round box attorno ai float! -->
    <div style="clear: both;"></div>
</div>

<?php
$conn->close();
?>