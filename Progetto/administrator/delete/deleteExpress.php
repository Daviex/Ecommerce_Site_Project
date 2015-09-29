<link rel="stylesheet" href="asset/styles/deleteExpress.css" type="text/css">

<script type="text/javascript">
    function deleteSingleExpress(IDExpress)
    {
        $.post(
            "delete/deleteExpress.php",
            {
                IDExpress: IDExpress
            },
            function(msg) {
                loadPageWithFXAjax(msg);
            }
        )
    }

    $(document).ready(function() {
        $("#choice1").click(function(){
            var choice = $("#choice1").val();
            var IDExpress = $("#IDExpress").val();

            $.post(
                "delete/deleteExpress.php",
                {
                    IDExpress: IDExpress,
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
            deleteExpress();
        });
    });
</script>

<?php
include("../../include/BasicLib.php");

?>
<div id="deleteexpressPage">
    <div class="title">
        Cancella un corriere
    </div>

    <?php
    if (!isset($_POST['IDExpress'])) {
        ?>
        <div class="infoExpress">
            <table>
                <tr>
                    <th>Corriere</th>
                    <th>Costo</th>
                    <th>Tempo di Consegna</th>
                </tr>
                <?php
                $SQL = "SELECT ID, Nominativo, Costo, Tempi_Consegna
                        FROM corrieri
                        ORDER BY ID";
                $result = $conn->query($SQL);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr onclick=\"deleteSingleExpress(" . $row['ID'] . ")\">";
                        echo "<td>" . $row['Nominativo'] . "</td>";
                        echo "<td>" . $row['Costo'] . "</td>";
                        echo "<td>" . $row['Tempi_Consegna'] . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
    <?php
    }
    elseif(!isset($_POST['choice'])) {
    $IDExpress = $_POST['IDExpress'];
    ?>
        <div class="infoExpress">
            <div class="message">
                Sei sicuro di voler cancellare questo corriere?
            </div>
            <input type="hidden" id="IDExpress" value="<?php echo $IDExpress; ?>">

            <br>

            <button id="choice1" value="1">Conferma</button>
            <br>
            <br>
            <button id="choice2" value="0">Annulla</button>
        </div>
    <?php
    }
    elseif(isset($_POST['choice']) && $_POST['choice'] == 1) {
    $IDExpress = $_POST['IDExpress'];

    $SQL = "DELETE FROM corrieri WHERE ID = '$IDExpress'";

    $result = $conn->query($SQL);

    if ($result) {
    echo "Corriere eliminato con successo!";
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