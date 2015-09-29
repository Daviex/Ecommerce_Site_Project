<link rel="stylesheet" href="./asset/styles/addCart.css" type="text/css">

<?php
include("../include/BasicLib.php");
?>

<div id="addcartPage">
    <div class="title">
        Prodotto aggiunto al carrello
    </div>
    <?php
    if(isset($_POST['IDProd']))
        $productID = $_POST['IDProd'];
    else
        $productID = -1;

    if($productID != -1) {
    if (@checkParameter($_POST['IDProd']) && @checkParameter($_POST['Quantita']) && $_POST['Quantita'] > 0) {
        //Access to JSON of the Cart. JSON is best way to keep data of my cart!
        $cartInfo = json_decode($_SESSION['cart'], true);

        $IDProd = $_POST['IDProd'];
        $Quantita = $_POST['Quantita'];
        $indexProd = null;

        for($i = 0; $i < count($cartInfo['cart']); $i++) {
            if((int)$cartInfo['cart'][$i]['IDProd'] == $IDProd)
            {
                $indexProd = $i;
                break;
            }
        }

        if (!isset($indexProd)) {
            $newInfo = array(
                "IDProd" => $IDProd,
                "Quantita" => $Quantita,
            );
            array_push($cartInfo['cart'], $newInfo);
        } else {
            $cartInfo['cart'][$indexProd]['Quantita'] = (int)$cartInfo['cart'][$indexProd]['Quantita'] + $Quantita;
        }

        $json = json_encode($cartInfo);

        $_SESSION['cart'] = $json;

        echo "Prodotto aggiunto con successo al carrello";
        ?>
        <script type="text/javascript">
            setTimeout(function(){ myCart() }, 1500);
        </script>
    <?php
    }
    else
    {
    echo "Errore! Verrai rimandato alla homepage!";
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
        echo "Errore! Verrai rimandato alla homepage!";
        ?>
        <script type="text/javascript">
            setTimeout(function(){ window.location.href = "index.php"; }, 1500);
        </script>
        <?php
    }
    ?>
</div>