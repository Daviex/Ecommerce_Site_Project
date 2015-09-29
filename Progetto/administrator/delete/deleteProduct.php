<link rel="stylesheet" href="asset/styles/deleteProduct.css" type="text/css">

<script type="text/javascript">
    function deleteSingleProduct(IDProd)
    {
        $.post(
            "delete/deleteProduct.php",
            {
                IDProd: IDProd
            },
            function(msg) {
                loadPageWithFXAjax(msg);
            }
        )
    }

    $(document).ready(function() {
       $("#choice1").click(function(){
           var choice = $("#choice1").val();
           var IDProd = $("#IDProd").val();

           $.post(
               "delete/deleteProduct.php",
               {
                   IDProd: IDProd,
                   choice: choice
               },
               function(msg) {
                   loadPageWithFXAjax(msg);
               }
           )
       });
    });

    $(document).ready(function() {
        $("#choice2").click(function(){
            deleteProd();
        });
    });
</script>

<?php
include("../../include/BasicLib.php");

?>
    <div id="deleteproductPage">
        <div class="title">
            Cancella un prodotto
        </div>

        <?php
        if (!isset($_POST['IDProd'])) {
            ?>
            <div class="infoProduct">
                <table>
                    <tr>
                        <th>Reparto</th>
                        <th>Categoria</th>
                        <th>Nome</th>
                        <th>Prezzo</th>
                        <th>Quantità</th>
                    </tr>
                    <?php
                    $SQL = "SELECT prod.ID as prodID, rep.Nome AS repNome, cat.Nome AS catNome, prod.Nome AS prodNome, prod.Prezzo, prod.Quantita
                            FROM ( prodotti AS prod LEFT JOIN categorie AS cat ON prod.IDCat = cat.ID) LEFT JOIN reparti AS rep ON cat.IDRep = rep.ID
                            ORDER BY prod.ID";
                    $result = $conn->query($SQL);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr onclick=\"deleteSingleProduct(" . $row['prodID'] . ")\">";
                            echo "<td>" . $row['repNome'] . "</td>";
                            echo "<td>" . $row['catNome'] . "</td>";
                            echo "<td>" . $row['prodNome'] . "</td>";
                            echo "<td>" . $row['Prezzo'] . " €</td>";
                            echo "<td>" . $row['Quantita'] . "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>
            </div>
        <?php
        }
        elseif(!isset($_POST['choice'])) {
            $IDProd = $_POST['IDProd'];
            ?>
        <div class="infoProduct">
            <div class="message">
                Sei sicuro di voler cancellare questo prodotto?
            </div>
            <input type="hidden" id="IDProd" value="<?php echo $IDProd; ?>">

            <br>

            <button id="choice1" value="1">Conferma</button>
            <br>
            <br>
            <button id="choice2" value="0">Annulla</button>
        </div>
            <?php
        }
        elseif(isset($_POST['choice']) && $_POST['choice'] == 1) {
            $IDProd = $_POST['IDProd'];

            $SQL = "SELECT cat.Nome AS catNome, prod.IMG as Image
                    FROM ( prodotti AS prod LEFT JOIN categorie AS cat ON prod.IDCat = cat.ID)
                    WHERE prod.ID = '$IDProd'";

            $prodInfo = $conn->query($SQL)->fetch_assoc();

            $imagePath = $root . "/products/images/" . strtolower($prodInfo['catNome']) . "/";
            unlink($imagePath . $prodInfo['Image']);

            $SQL = "DELETE FROM prodotti WHERE ID = '$IDProd'";

            $result = $conn->query($SQL);

            if ($result) {
                echo "Prodotto eliminato con successo!";
                ?>
                <script type="text/javascript">
                    setTimeout(function () {
                        homepage();
                    }, 1500);
                </script>
            <?php
            }
            else
                echo "Errore 1: " . mysql_error($conn);
        }
        ?>
    </div>
<?php

$conn->close();
?>