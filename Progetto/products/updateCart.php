<link rel="stylesheet" href="./asset/styles/updateCart.css" type="text/css">

<?php
include("../include/BasicLib.php");
?>

<div id="updatecartPage">
    <div class="title">
        Carrello aggiornato
    </div>
    <?php
    if(isset($_GET['IDProd']))
        $productID = $_GET['IDProd'];
    else
        $productID = -1;

    if($productID != -1) {
    if (@checkParameter($_GET['IDProd']) && @checkParameter($_GET['Quantity']) && $_GET['Quantity'] > 0) {
        //Access to JSON of the Cart. JSON is best way to keep data of my cart!
        $cartInfo = json_decode($_SESSION['cart'], true);

        $IDProd = $_GET['IDProd'];
        $Quantita = $_GET['Quantity'];
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
            $cartInfo['cart'][$indexProd]['Quantita'] = $Quantita;
        }

        $json = json_encode($cartInfo);

        $_SESSION['cart'] = $json;
        ?>
        <script type="text/javascript">
            myCart();
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
    $conn->close();
    ?>
</div>