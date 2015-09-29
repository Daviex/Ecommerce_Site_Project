<link rel="stylesheet" href="asset/styles/manageState.css" type="text/css">

<script type="text/javascript">
    function manageSingleState(IDState)
    {
        $.post(
            "manage/manageState.php",
            {
                IDState: IDState
            },
            function(msg) {
                loadPageWithFXAjax(msg);
            }

        );
    }
    $(document).ready(function() {
        $("#submit").click(function() {
            var IDState = $("#stateID").val();
            var Nome = $("#name").val();

            $.post(
                "manage/manageState.php",
                {
                    IDState: IDState,
                    Nome: Nome
                },
                function(msg) {
                    loadPageWithFXAjax(msg);
                }
            );
        });
    });
</script>

<?php
include("../../include/BasicLib.php");
?>

<div id="managestatePage">
    <div class="title">
        Modifica uno stato
    </div>
    <?php
    if(!isset($_POST['IDState'])){
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
                        echo "<tr onclick=\"manageSingleState(" . $row['ID'] . ")\">";
                        echo "<td>" . $row['Nome'] . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
    <?php
    }
    elseif (!isset($_POST['Nome']) && isset($_POST['IDState'])) {
    ?>
    <div class="infoState">
        <?php
        $IDState = $_POST['IDState'];
        $SQL = "SELECT ID, Nome
                FROM stati
                WHERE ID = '$IDState'";

        $result = $conn->query($SQL);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            Nome:<br><input id="name" name="Nome" value="<?php echo $row['Nome'] ?>" type="text"><br><br>

            <input type="hidden" id="stateID" value="<?php echo $IDState; ?>" >

            <button id="submit">Conferma</button>
        <?php
        }
        ?>
    </div>
</div>
<?php
}
else {
    $IDState = $_POST['IDState'];

    $SQL = "SELECT ID, Nome
                FROM stati
                WHERE ID = '$IDState'";

    $result = $conn->query($SQL);

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        $oldstateNome = $row['Nome'];

        $Nome = $_POST['Nome'];

        if ($Nome == "") {
            echo "Il nome della categoria è vuota, riprova!";
            return;
        }

        $SQL = "";

        if ($Nome != $oldstateNome)
        {
            $SQL = "UPDATE stati SET Nome = '".$conn->real_escape_string($Nome)."' WHERE ID = '$IDState'; ";
        }

        if($SQL != "")
        {
            $result = $conn->query($SQL) or die($conn->error);

            if ($result) {
                echo "Stato aggiornato con successo!";
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
            echo "Stato non aggiornato";
    }
}
?>