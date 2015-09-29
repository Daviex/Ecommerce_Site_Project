<link rel="stylesheet" href="asset/styles/manageSection.css" type="text/css">

<script type="text/javascript">
    function manageSingleDepartment(IDRep)
    {
        $.post(
            "manage/manageSection.php",
            {
                IDRep: IDRep
            },
            function(msg) {
                loadPageWithFXAjax(msg);
            }

        );
    }
    $(document).ready(function() {
        $("#submit").click(function() {
            var IDRep = $("#departmentID").val();
            var Nome = $("#name").val();
            var Desc = $("#desc").val();

            $.post(
                "manage/manageSection.php",
                {
                    IDRep: IDRep,
                    Nome: Nome,
                    Desc: Desc
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

<div id="managesectionPage">
    <div class="title">
        Modifica una sezione
    </div>
    <?php
    if(!isset($_POST['IDRep'])){
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
                        echo "<tr onclick=\"manageSingleDepartment(" . $row['repID'] . ")\">";
                        echo "<td>" . $row['repNome'] . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
    <?php
    }
    elseif (!isset($_POST['Nome']) || !isset($_POST['Desc']) && isset($_POST['IDRep'])) {
    ?>
    <div class="infoSection">
        <?php
        $IDRep = $_POST['IDRep'];

        $SQL = "SELECT rep.Nome AS repNome, rep.Desc AS repDesc, rep.ID AS repID
        FROM reparti AS rep
        WHERE rep.ID = '$IDRep'";
        $result = $conn->query($SQL);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            Nome:<br><input id="name" name="Nome" value="<?php echo $row['repNome'] ?>" type="text"><br><br>

            Descrizione:<br><textarea id="desc" name="Desc" cols="80" rows="16"><?php echo $row['repDesc'] ?></textarea><br><br>

            <input type="hidden" id="departmentID" value="<?php echo $IDRep; ?>" >

            <button id="submit">Conferma</button>
        <?php
        }
        ?>
    </div>
</div>
<?php
}
else {
    $IDRep = $_POST['IDRep'];

    $SQL = "SELECT rep.Nome AS repNome, rep.Desc AS repDesc, rep.ID AS repID
            FROM reparti AS rep
            WHERE rep.ID = '$IDRep'";

    $result = $conn->query($SQL);

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        $oldrepNome = $row['repNome'];
        $oldrepDesc = $row['repDesc'];

        $Nome = $_POST['Nome'];
        $Desc = $_POST['Desc'];

        if ($Nome == "") {
            echo "Il nome della categoria è vuota, riprova!";
            return;
        }

        if ($Desc == "") {
            echo "La descrizione della categoria è vuota, riprova!";
            return;
        }

        $SQL = "";

        if ($Nome != $oldrepNome)
        {
            $SQL .= "UPDATE reparti SET Nome = '".$conn->real_escape_string($Nome)."' WHERE ID = '$IDRep'; ";
        }

        if ($Desc != $oldrepDesc)
        {
            $SQL .= "UPDATE reparti SET `Desc` = '".$conn->real_escape_string($Desc)."' WHERE ID = '$IDRep';";
        }

        if($SQL != "")
        {
            $result = $conn->multi_query($SQL) or die($conn->error);

            if ($result) {
                echo "Reparto aggiornato con successo!";
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
            echo "Reparto non aggiornato";
    }
}
?>