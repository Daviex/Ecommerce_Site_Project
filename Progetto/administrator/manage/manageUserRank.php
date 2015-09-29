<link rel="stylesheet" href="asset/styles/manageUserRank.css" type="text/css">

<script type="text/javascript">
    function manageSingleUserRank(IDUser)
    {
        $.post(
            "manage/manageUserRank.php",
            {
                IDUser: IDUser
            },
            function(msg) {
                loadPageWithFXAjax(msg);
            }

        );
    }
    $(document).ready(function() {
        $("#submit").click(function() {
            var IDUser = $("#userID").val();
            var Rank = $("#rank").val();

            $.post(
                "manage/manageState.php",
                {
                    IDUser: IDUser,
                    Rank: Rank
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

<div id="manageuserrankPage">
    <div class="title">
        Modifica un rank utente
    </div>
    <?php
    if(!isset($_POST['IDUser'])){
        ?>
        <div class="infoRank">
            <table>
                <tr>
                    <th>Nome Utente</th>
                </tr>
                <?php
                $SQL = "SELECT ID, Username
                        FROM utenti
                        ORDER BY Nome";
                $result = $conn->query($SQL);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr onclick=\"manageSingleUserRank(" . $row['ID'] . ")\">";
                        echo "<td>" . $row['Username'] . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
    <?php
    }
    elseif (!isset($_POST['Rank']) && isset($_POST['IDUser'])) {
    ?>
    <div class="infoRank">
        <?php
        $IDUser = $_POST['IDUser'];
        $SQL = "SELECT ID, Username, Rank
                FROM utenti
                WHERE ID = '$IDUser'";

        $result = $conn->query($SQL);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            Username: <?php echo $row['Username'] ?><br><br>
            Rank Corrente: <?php

            if($row['Rank'] == 1)
                echo "Utente Normale";
            elseif($row['Rank'] == 2)
                echo "Operatore";
            elseif($row['Rank'] > 3)
                echo "Amministratore";
            else
                echo "Non impostato!";
            ?>
            <br><br>

            Rank: <select name="Rank">
                <option>Seleziona un rank</option>
                <option value="1">Utente Normale</option>
                <option value="2">Operatore</option>
                <option value="3">Amministratore</option>
            </select><br><br>

            <input type="hidden" id="userID" value="<?php echo $IDUser; ?>" >

            <button id="submit">Conferma</button>
        <?php
        }
        ?>
    </div>
</div>
<?php
}
else {
    $IDUser = $_POST['IDUser'];

    $SQL = "SELECT ID, Rank
                FROM utenti
                WHERE ID = '$IDUser'";

    $result = $conn->query($SQL);

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        $olduserRank = $row['Rank'];

        $Rank = $_POST['Nome'];

        $SQL = "";

        if ($Rank != $olduserRank)
        {
            $SQL = "UPDATE user SET Rank = '".$conn->real_escape_string($Rank)."' WHERE ID = '$IDUser'; ";
        }

        if($SQL != "")
        {
            $result = $conn->query($SQL) or die($conn->error);

            if ($result) {
                echo "Rank aggiornato con successo!";
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
            echo "Rank non aggiornato";
    }
}
?>