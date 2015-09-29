<link rel="stylesheet" href="asset/styles/homepage.css" type="text/css">

<h1>Homepage</h1>
<?php
    include("include/BasicLib.php");
?>

<div id="homepagePage">
    <div class="title">
        I 10 prodotti più venduti!
    </div>

    <?php
    $SQL = "SELECT
      COUNT(*) AS NumAcq,
      cat.Nome AS catNome,
      prod.ID AS prodID,
      prod.Nome AS prodNome,
      prod.Desc AS prodDesc,
      prod.Prezzo AS prodPrice,
      prod.IMG AS prodIMG
    FROM ( categorie AS cat RIGHT JOIN prodotti as prod ON cat.ID = prod.IDCat ) JOIN acquisti AS acq ON acq.IDProd = prod.ID
    GROUP BY acq.IDProd
    ORDER BY NumAcq DESC
    LIMIT 10";

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
                    if(strlen($prodDesc) >= 378)
                        $prodDesc = substr($prodDesc, 0, 378)."...";
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
    <div style="clear: both;"></div></div>

<?php
	$conn->close();
?>

