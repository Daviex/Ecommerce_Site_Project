<link rel="stylesheet" href="./asset/styles/removeReview.css" type="text/css">

<?php
include("../include/BasicLib.php");
?>
<script type="text/javascript">
    $(document).ready(function(){
       $("#choice1").click(function(){
           var IDProd = $("#idProd").val();
           var Choice = $("#choice1").val();

           $.post(
               "products/removeReview.php",
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
            var IDProd = $("#idProd").val();
            product(IDProd);
        });
    });
</script>

<div id="removereviewPage">
    <div class="title">
        Rimuovi recensione
    </div>

<?php
if(isset($_POST['Choice'])) {
    $productID = $_POST['idProd'];
    if ($_POST['Choice'] == 1) {
        $SQL = "SELECT acq.ID AS IDAcq
                FROM acquisti AS acq JOIN ordini AS ord ON acq.IDOrdine = ord.ID
                WHERE acq.IDProd = '$productID' AND ord.IDUser = '$idSession'";

        $result = $conn->query($SQL);

        if ($result->num_rows > 0) {
            $IDAcq = $result->fetch_assoc()['IDAcq'];

            $SQL = "DELETE FROM recensioni WHERE IDAcq = '$IDAcq'";

            $result = $conn->query($SQL);
            if ($result) {
                echo "Recensione rimossa... Ritorno alla pagina del prodotto...";
                ?>
                <script type="text/javascript">
                    setTimeout(function () {
                        product(<?php echo $productID; ?>)
                    }, 1500);
                </script>
                <?php
            }
            else {
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
    } elseif ($_POST['Choice'] == 0) {
        echo "Rimozione annullata... Ritorno alla pagina del prodotto...";
        ?>
            <script type="text/javascript">
                setTimeout(function () {
                    product(<?php echo $productID; ?>)
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
}
else {
    if (isset($_GET['idProd']))
        $productID = $_GET['idProd'];
    else
        $productID = -1;
    ?>
    <div class="message">
        Sei sicuro di voler cancellare la tua recensione su questo prodotto?
    </div>
    <input type="hidden" id="idProd" value="<?php echo $productID; ?>">

    <div class="button">
        <button id="choice1" value="1">Conferma</button>
    </div>
    <br>
    <div class="button">
        <button id="choice2" value="0">Annulla</button>
    </div>
<?php
}
$conn->close();
?>
</div>