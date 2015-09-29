<link rel="stylesheet" href="asset/styles/manageDiscount.css" type="text/css">

<?php
include("../include/BasicLib.php");
?>

<script type="text/javascript">
    function manageDiscount(IDProd) {
        $.post(
            "manageDiscount.php",
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
           var Discount = $("#discount").val();

           $.post(
               "manageDiscount.php",
               {
                   IDProd: IDProd,
                   Discount: Discount
               },
               function(msg) {
                   loadPageWithFXAjax(msg);
               }
           )
       });
    });

    $(document).ready(function() {
        $("#remove").click(function() {
            var IDProd = $("#productID").val();
            var RemoveDiscount = true;

            $.post(
                "manageDiscount.php",
                {
                    IDProd: IDProd,
                    RemoveDiscount: RemoveDiscount
                },
                function(msg) {
                    loadPageWithFXAjax(msg);
                }
            )
        });
    });
</script>

<div id="managediscountPage">
    <div class="title">
    Inserisci un nuovo sconto!
    </div>
    <div class="infoDiscount">
        <?php
        if(!isset($_POST['IDProd']) && !isset($_POST['RemoveDiscount'])) {
            ?>
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
                        echo "<tr onclick=\"manageDiscount(" . $row['prodID'] . ")\">";
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
        <?php
        }
        elseif(!isset($_POST['Discount']) && isset($_POST['IDProd']) && !isset($_POST['RemoveDiscount'])) {
        ?>
            <?php
            $IDProd = $_POST['IDProd'];
            $SQL = "SELECT prod.Nome, scon.Percentuale
                    FROM prodotti AS prod LEFT JOIN sconti as scon ON prod.ID = scon.IDProd
                    WHERE prod.ID = '$IDProd'";

            $result = $conn->query($SQL);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                ?>
                Inserisci la percentuale di sconto da applicare al prodotto: <br><?php echo $row['Nome']; ?><br>

                <?php
                if(!empty($row['Percentuale']))
                {
                    ?>
                        <span style="color: red">Sconto attuale del: <?php echo $row['Percentuale']; ?>%</span>
                    <?php
                }
                ?>

        <br><br>

                Percentuale:&nbsp;<input type="number" id="discount" name="Discount" value="<?php echo $prodPrice; ?>" min="1" max="100"><br><br>

                <input type="hidden" id="productID" value="<?php echo $IDProd; ?>" >

                <button id="submit">Conferma</button>

                <?php
                if(!empty($row['Percentuale']))
                {
                    ?>
                    <button id="remove">Rimuovi Sconto</button>
                    <?php
                }
                ?>
            <?php
            }
            ?>
        <?php
        }
        elseif(isset($_POST['RemoveDiscount']) && isset($_POST['IDProd'])) {
            if($_POST['RemoveDiscount'])
            {
                $IDProd = $_POST['IDProd'];
                $SQL = "DELETE FROM sconti WHERE IDProd = '$IDProd'";

                if($conn->query($SQL))
                {
                    echo "Sconto rimosso con successo!";
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
        else {
            $IDProd = $_POST['IDProd'];
            $Discount = $_POST['Discount'];

            /* Check if there's already another discount on this product */
            $SQL = "SELECT * FROM sconti WHERE IDProd = '$IDProd'";

            if($conn->query($SQL)->num_rows > 0)
            {
                /* So, I do an UPDATE of the discount */
                $SQL = "UPDATE sconti SET Percentuale = '$Discount' WHERE IDProd = '$IDProd'";

                if($conn->query($SQL))
                {
                    echo "Sconto aggiornato con successo!";
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
            else
            {
                /* I add the discount to the product*/
                    $SQL = "INSERT INTO sconti (Percentuale, IDProd) VALUES ('".$Discount."', '".$IDProd."')";

                    if($conn->query($SQL))
                    {
                        echo "Sconto aggiunto con successo!";
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