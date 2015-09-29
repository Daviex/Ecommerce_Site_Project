<link rel="stylesheet" href="./asset/styles/category.css" type="text/css">

<script type="text/javascript">
    $(document).ready(function(){
       $("#filter").change(function(){
           var idCat = $("#category").val();
           var choice = $("#filter").val();

           console.log(choice);

           $.get(
             "section/category.php",
               {
                   idCat : idCat,
                   choice : choice
               },
               function(msg) {
                   loadPageWithFXAjax(msg);
               }
           );
       });
    });
</script>

<?php
include("../include/BasicLib.php");

if(isset($_GET['idCat']))
    $categoryID = $_GET['idCat'];
else
    $categoryID = -1;

$SQL = "SELECT * FROM categorie WHERE ID = '$categoryID'";

$result = $conn->query($SQL);

if($result->num_rows > 0)
{
    $row = $result->fetch_assoc();

    $currentCategoryName = $row['Nome'];
    $currentCategoryDescription = $row['Desc'];

//TODO: PERFECT COUNT FOR INFOPRODUCT IS ABOUT 378 Chars, where the last three are dots!
    ?>

    <div id="categoryPage">
        <div class="title">
            Prodotti  disponili per <?php echo $currentCategoryName; ?>
        </div>

            <select id="filter" class="filter">
                <option>Scegli un filtro</option>
                <option value="priceAsc">Prezzo crescente</option>
                <option value="priceDesc">Prezzo decresente</option>
                <option value="nameAsc">Nome crescente</option>
                <option value="nameDesc">Nome decrescente</option>
            </select>
            <input type="hidden" id="category" value="<?php echo $categoryID; ?>">

        <br><br><br>
        <div class="description">
            <?php
            echo $currentCategoryDescription;
            ?>
        </div>
        <div style="clear: both;"></div>

        <?php

        /* Filter Settings */
        if(isset($_GET['choice']))
            $choice = $_GET['choice'];
        else
            $choice = "priceAsc";

        switch($choice)
        {
            case 'priceAsc':
                $filterBy = "ORDER BY prodPrice ASC";
                break;
            case 'priceDesc':
                $filterBy = "ORDER BY prodPrice DESC";
                break;
            case 'nameAsc':
                $filterBy = "ORDER BY prodNome ASC";
                break;
            case 'nameDesc':
                $filterBy = "ORDER BY prodNome DESC";
                break;
            default:
                $filterBy = "ORDER BY prodPrice ASC";
                break;
        }

        $SQL = "SELECT
          cat.ID AS catID,
          cat.Nome AS catNome,
          cat.Desc AS catDesc,
          prod.ID AS prodID,
          prod.Nome AS prodNome,
          prod.Desc AS prodDesc,
          prod.Prezzo AS prodPrice,
          prod.IMG AS prodIMG
        FROM ( categorie AS cat LEFT JOIN prodotti as prod
          ON cat.ID = prod.IDCat )
        WHERE prod.IDCat ='$categoryID'";

        $SQL .= $filterBy;

        $result = $conn->query($SQL);

        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc()) {
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
                        echo "<img src=\"products/images/".strtolower($catNome)."/".$prodIMG."\" width=\"120px\" height=\"120px\">";
                        ?>
                    </div>
                    <div class="productName">
                        <?php
                        echo "<a href=\"#\" onclick=\"product(".$prodID.")\">".$prodName."</a><br>";
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
        <div style="clear: both;"></div>
    </div>

<?php
}
else
{
    echo "C'è stato un errore... Verrai rimandato alla home!";
    ?>
    <script type="text/javascript">
        setTimeout(function () {
            window.location.href = "index.php";
        }, 1500);
    </script>
<?php
}
$conn->close();
?>