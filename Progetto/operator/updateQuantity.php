<link rel="stylesheet" href="asset/styles/updateQuantity.css" type="text/css">

<?php
include("../include/BasicLib.php");
?>

<script type="text/javascript">
    function updateQuantity(IDProd) {
        $.post(
            "updateQuantity.php",
            {
                IDProd: IDProd
            },
            function (msg) {
                loadPageWithFXAjax(msg);
            }
        );
    }

    $(document).ready(function() {
        $("#submit").click(function() {
            var IDProd = $("#productID").val();
            var Quantity = $("#quantity").val();

            $.post(
                "updateQuantity.php",
                {
                    IDProd: IDProd,
                    Quantity: Quantity
                },
                function(msg) {
                    loadPageWithFXAjax(msg);
                }
            )
        });
    });
</script>

<div id="updatequantityPage">
    <div class="title">
        Aggiorna quantità di un prodotto
    </div>
    <div class="infoQuantity">
        <?php
        if(!isset($_POST['IDProd'])) {
            ?>
            <table>
                <tr>
                    <th>Reparto</th>
                    <th>Categoria</th>
                    <th>Nome</th>
                    <th>Quantità</th>
                </tr>
                <?php
                $SQL = "SELECT prod.ID as prodID, rep.Nome AS repNome, cat.Nome AS catNome, prod.Nome AS prodNome, prod.Prezzo, prod.Quantita
            FROM ( prodotti AS prod LEFT JOIN categorie AS cat ON prod.IDCat = cat.ID) LEFT JOIN reparti AS rep ON cat.IDRep = rep.ID
            ORDER BY prod.ID";
                $result = $conn->query($SQL);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr onclick=\"updateQuantity(" . $row['prodID'] . ")\">";
                        echo "<td>" . $row['repNome'] . "</td>";
                        echo "<td>" . $row['catNome'] . "</td>";
                        echo "<td>" . $row['prodNome'] . "</td>";
                        echo "<td>" . $row['Quantita'] . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        <?php
        }
        elseif(isset($_POST['IDProd']) && !isset($_POST['Quantity'])) {
        ?>
            <?php
            $IDProd = $_POST['IDProd'];
            $SQL = "SELECT prod.Nome, prod.Quantita
                    FROM prodotti AS prod
                    WHERE prod.ID = '$IDProd'";

            $result = $conn->query($SQL);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $Quantita = $row['Quantita'];
            ?>
            Inserisci la quantità del prodotto: <br><?php echo $row['Nome']; ?><br>

        <br><br>

            Quantità:&nbsp;<input type="number" id="quantity" name="Quantity" value="<?php echo $Quantita; ?>"
                                  min="0"><br><br>

        <input type="hidden" id="productID" value="<?php echo $IDProd; ?>">

            <button id="submit">Conferma</button>
        <?php
        }
        }
        else {
            $IDProd = $_POST['IDProd'];
            $Quantita = $_POST['Quantity'];

            /* Check if there's already another discount on this product */
            $SQL = "SELECT * FROM prodotti WHERE ID = '$IDProd'";

            if ($conn->query($SQL)->num_rows > 0)
            {
                /* So, I do an UPDATE of the discount */
                $SQL = "UPDATE prodotti SET Quantita = '$Quantita' WHERE ID = '$IDProd'";

            if ($conn->query($SQL))
            {
                echo "Quantità prodotto aggiornata con successo!";
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
        }
        ?>
    </div>
</div>
<?php
$conn->close();
?>