<link rel="stylesheet" href="asset/styles/manageExpress.css" type="text/css">

<script type="text/javascript">
    function manageSingleExpress(IDExpress)
    {
        $.post(
            "manage/manageExpress.php",
            {
                IDExpress: IDExpress
            },
            function(msg) {
                loadPageWithFXAjax(msg);
            }

        );
    }
    $(document).ready(function() {
        $("#submit").click(function() {
            var IDExpress = $("#expressID").val();
            var Nome = $("#name").val();
            var Costo = $("#price").val();
            var Tempo = $("#time").val();

            $.post(
                "manage/manageExpress.php",
                {
                    IDExpress: IDExpress,
                    Nome: Nome,
                    Costo: Costo,
                    Tempo: Tempo
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

<div id="manageexpressPage">
    <div class="title">
        Modifica un corriere
    </div>
    <?php
    if(!isset($_POST['IDExpress'])){
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
                        echo "<tr onclick=\"manageSingleExpress(" . $row['ID'] . ")\">";
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
    elseif (!isset($_POST['Nome']) || !isset($_POST['Costo']) || !isset($_POST['Tempo']) && isset($_POST['IDExpress'])) {
    ?>
    <div class="infoExpress">
        <?php
        $IDExpress = $_POST['IDExpress'];

        $SQL = "SELECT ID, Nominativo, Costo, Tempi_Consegna
                FROM corrieri
                WHERE ID = '$IDExpress'";

        $result = $conn->query($SQL);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            Nome:<br><input id="name" name="Nome" value="<?php echo $row['Nominativo'] ?>" type="text"><br><br>

            Costo:<br><input type="number" id="price" name="Prezzo" value="<?php echo $row['Costo']; ?>" min="0.01" step="0.01">
            Euro<br><br>

            Nome:<br><input id="time" name="Tempo" value="<?php echo $row['Tempi_Consegna'] ?>" type="text"><br><br>

            <input type="hidden" id="expressID" value="<?php echo $IDExpress; ?>" >

            <button id="submit">Conferma</button>
        <?php
        }
        ?>
    </div>
</div>
<?php
}
else {
    $IDExpress = $_POST['IDExpress'];

    $SQL = "SELECT ID, Nominativo, Costo, Tempi_Consegna
            FROM corrieri
            WHERE ID = '$IDExpress'";

    $result = $conn->query($SQL);

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        $oldexpressNome = $row['Nominativo'];
        $oldexpressCosto = $row['Costo'];
        $oldexpressTempo = $row['Tempi_Consegna'];

        $Nome = $_POST['Nome'];
        $Costo = $_POST['Costo'];
        $Tempo = $_POST['Tempo'];

        if ($Nome == "") {
            echo "Il nome del corriere è vuoto, riprova!";
            return;
        }

        if (!is_numeric($Costo)) {
            echo "Il costo non è numerico, riprova!";
            return;
        }

        if ($Tempo == "") {
            echo "Il tempo di consegna è vuoto, riprova!";
            return;
        }

        $SQL = "";

        if ($Nome != $oldexpressNome)
        {
            $SQL = "UPDATE corrieri SET Nome = '".$conn->real_escape_string($Nome)."' WHERE ID = '$IDExpress'; ";
        }

        if ($Costo != $oldexpressCosto)
        {
            $SQL = "UPDATE corrieri SET Costo = '".$conn->real_escape_string($Costo)."' WHERE ID = '$IDExpress'; ";
        }

        if ($Tempo != $oldexpressTempo)
        {
            $SQL = "UPDATE corrieri SET Tempi_Consegna = '".$conn->real_escape_string($Tempo)."' WHERE ID = '$IDExpress'; ";
        }

        if($SQL != "")
        {
            $result = $conn->multi_query($SQL) or die($conn->error);

            if ($result) {
                echo "Corriere aggiornato con successo!";
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
            echo "Corriere non aggiornato";
    }
}
?>