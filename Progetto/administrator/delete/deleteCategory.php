<link rel="stylesheet" href="asset/styles/deleteCategory.css" type="text/css">

<script type="text/javascript">
    function deleteSingleCategory(IDCat)
    {
        $.post(
            "delete/deleteCategory.php",
            {
                IDCat: IDCat
            },
            function(msg) {
                loadPageWithFXAjax(msg);
            }
        )
    }

    $(document).ready(function() {
        $("#choice1").click(function(){
            var choice = $("#choice1").val();
            var IDCat = $("#IDCat").val();

            $.post(
                "delete/deleteCategory.php",
                {
                    IDCat: IDCat,
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
            deleteCat();
        });
    });
</script>

<?php
include("../../include/BasicLib.php");

?>
<div id="deletecategoryPage">
    <div class="title">
        Cancella una categoria
    </div>

    <?php
    if (!isset($_POST['IDCat'])) {
        ?>
        <div class="infoCategory">
            <table>
                <tr>
                    <th>Reparto</th>
                    <th>Categoria</th>
                </tr>
                <?php
                $SQL = "SELECT rep.Nome AS repNome, cat.Nome AS catNome, cat.ID AS catID
                            FROM categorie AS cat LEFT JOIN reparti AS rep ON cat.IDRep = rep.ID
                            ORDER BY cat.ID";
                $result = $conn->query($SQL);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr onclick=\"deleteSingleCategory(" . $row['catID'] . ")\">";
                        echo "<td>" . $row['repNome'] . "</td>";
                        echo "<td>" . $row['catNome'] . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
    <?php
    }
    elseif(!isset($_POST['choice'])) {
    $IDCat = $_POST['IDCat'];
    ?>
        <div class="infoCategory">
            <div class="message">
                Sei sicuro di voler cancellare questa categoria?
            </div>
            <input type="hidden" id="IDCat" value="<?php echo $IDCat; ?>">

            <br>

            <button id="choice1" value="1">Conferma</button>
            <br>
            <br>
            <button id="choice2" value="0">Annulla</button>
        </div>
    <?php
    }
    elseif(isset($_POST['choice']) && $_POST['choice'] == 1) {
        $IDCat = $_POST['IDCat'];

        $SQL = "SELECT rep.Nome AS repNome, cat.Icon as Image
                        FROM categorie AS cat LEFT JOIN reparti AS rep ON cat.IDRep = rep.ID
                        WHERE cat.ID = '$IDCat'";

        $catInfo = $conn->query($SQL)->fetch_assoc();

        $imagePath = $root . "/section/images/" . strtolower($catInfo['repNome']) . "/";
        unlink($imagePath . $catInfo['Image']);

        $SQL = "DELETE FROM categorie WHERE ID = '$IDCat'";

        $result = $conn->query($SQL);

        if ($result) {
            echo "Categoria eliminata con successo!";
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