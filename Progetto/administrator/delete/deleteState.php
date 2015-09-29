<link rel="stylesheet" href="asset/styles/deleteState.css" type="text/css">

<script type="text/javascript">
    function deleteSingleState(IDState)
    {
        $.post(
            "delete/deleteState.php",
            {
                IDState: IDState
            },
            function(msg) {
                loadPageWithFXAjax(msg);
            }
        )
    }

    $(document).ready(function() {
        $("#choice1").click(function(){
            var choice = $("#choice1").val();
            var IDState = $("#IDState").val();

            $.post(
                "delete/deleteState.php",
                {
                    IDState: IDState,
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
            deleteState();
        });
    });
</script>

<?php
include("../../include/BasicLib.php");

?>
<div id="deletestatePage">
    <div class="title">
        Cancella uno stato
    </div>

    <?php
    if (!isset($_POST['IDState'])) {
        ?>
        <div class="infoState">
            <table>
                <tr>
                    <th>Stato</th>
                </tr>
                <?php
                $SQL = "SELECT ID, Nome
                        FROM stati
                        ORDER BY ID";
                $result = $conn->query($SQL);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr onclick=\"deleteSingleState(" . $row['ID'] . ")\">";
                        echo "<td>" . $row['Nome'] . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
    <?php
    }
    elseif(!isset($_POST['choice'])) {
    $IDState = $_POST['IDState'];
    ?>
        <div class="infoState">
            <div class="message">
                Sei sicuro di voler cancellare questo stato?
            </div>
            <input type="hidden" id="IDState" value="<?php echo $IDState; ?>">

            <br>

            <button id="choice1" value="1">Conferma</button>
            <br>
            <br>
            <button id="choice2" value="0">Annulla</button>
        </div>
    <?php
    }
    elseif(isset($_POST['choice']) && $_POST['choice'] == 1) {
    $IDState = $_POST['IDState'];

    $SQL = "DELETE FROM stati WHERE ID = '$IDState'";

    $result = $conn->query($SQL);

    if ($result) {
    echo "Stato eliminato con successo!";
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