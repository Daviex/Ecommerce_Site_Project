<link rel="stylesheet" href="asset/styles/deleteSection.css" type="text/css">

<script type="text/javascript">
    function deleteSingleSection(IDRep)
    {
        $.post(
            "delete/deleteSection.php",
            {
                IDRep: IDRep
            },
            function(msg) {
                loadPageWithFXAjax(msg);
            }
        )
    }

    $(document).ready(function() {
        $("#choice1").click(function(){
            var choice = $("#choice1").val();
            var IDRep = $("#IDRep").val();

            $.post(
                "delete/deleteSection.php",
                {
                    IDRep: IDRep,
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
            deleteSec();
        });
    });
</script>

<?php
include("../../include/BasicLib.php");

?>
<div id="deletesectionPage">
    <div class="title">
        Cancella un reparto
    </div>

    <?php
    if (!isset($_POST['IDRep'])) {
        ?>
        <div class="infoSection">
            <table>
                <tr>
                    <th>Reparto</th>
                </tr>
                <?php
                $SQL = "SELECT rep.Nome AS repNome, rep.ID AS repID
                            FROM reparti AS rep
                            ORDER BY rep.ID";
                $result = $conn->query($SQL);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr onclick=\"deleteSingleSection(" . $row['repID'] . ")\">";
                        echo "<td>" . $row['repNome'] . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
    <?php
    }
    elseif(!isset($_POST['choice'])) {
    $IDRep = $_POST['IDRep'];
    ?>
        <div class="infoSection">
            <div class="message">
                Sei sicuro di voler cancellare questo reparto?
            </div>
            <input type="hidden" id="IDRep" value="<?php echo $IDRep; ?>">

            <br>

            <button id="choice1" value="1">Conferma</button>
            <br>
            <br>
            <button id="choice2" value="0">Annulla</button>
        </div>
    <?php
    }
    elseif(isset($_POST['choice']) && $_POST['choice'] == 1) {
    $IDRep = $_POST['IDRep'];

    $SQL = "DELETE FROM reparti WHERE ID = '$IDRep'";

    $result = $conn->query($SQL);

    if ($result) {
    echo "Reparto eliminato con successo!";
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