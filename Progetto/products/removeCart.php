<link rel="stylesheet" href="./asset/styles/removeCart.css" type="text/css">

<?php
include("../include/BasicLib.php");
?>

<script type="text/javascript">
    $(document).ready(function(){
        $("#choice1").click(function(){
            var IDProd = $("#idProd").val();
            var Choice = $("#choice1").val();

            $.post(
                "products/removeCart.php",
                {
                    idProd : IDProd,
                    Choice : Choice
                },
                function(msg){
                    loadPageWithFXAjax(msg);
                }
            )
        });
    });

    $(document).ready(function(){
        $("#choice2").click(function(){
            myCart();
        });
    });
</script>

<?php
    if (isset($_GET['IDProd']))
            $productID = $_GET['IDProd'];
        else
            $productID = -1;

    if(isset($_POST['Choice']) && isset($productID)) {
        $productID = $_POST['idProd'];
        if ($_POST['Choice'] == 1) {
            //Access to JSON of the Cart. JSON is best way to keep data of my cart!
            $cartInfo = json_decode($_SESSION['cart'], true);

            $newArray = array();

            for ($i = 0; $i < count($cartInfo['cart']); $i++) {
                if ($cartInfo['cart'][$i]['IDProd'] != $productID) {
                    array_push($newArray, $cartInfo['cart'][$i]);
                }
            }

            $cartInfo['cart'] = $newArray;

            $json = json_encode($cartInfo);

            $_SESSION['cart'] = $json;
            ?>
            <script type="text/javascript">
                myCart();
            </script>
            <?php
        } elseif ($_POST['Choice'] == 0) {
            echo "Rimozione annullata... Ritorno al carrello...";
            ?>
            <script type="text/javascript">
                setTimeout(function () {
                    myCart()
                }, 1500);
            </script>
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
        if ($productID != -1 && $productID != -2) {
            if (@checkParameter($productID)) {
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
        } elseif ($productID == -2) {
            unset($_SESSION['cart']);
            ?>
            <script type="text/javascript">
                myCart();
            </script>
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

    }
    else {
        ?>
        <div id="removecartPage">
        <div class="title">
            Rimozione prodotto dal carrello
        </div>
        <div class="message">
            Sei sicuro di voler rimuovere questo prodotto dal tuo carrello?
        </div>
        <input type="hidden" id="idProd" value="<?php echo $productID; ?>">

        <div class="button">
            <button id="choice1" value="1">Conferma</button>
        </div>
        <br>

        <div class="button">
            <button id="choice2" value="0">Annulla</button>
        </div>
        </div>
    <?php
    }
    $conn->close();
?>

