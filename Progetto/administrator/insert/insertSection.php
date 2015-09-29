<link rel="stylesheet" href="asset/styles/insertSection.css" type="text/css">

<script type="text/javascript">
    $(document).ready(function() {
       $("#submit").click(function() {
           var Nome = $("#name").val();
           var Desc = $("#desc").val();

           $.post(
               "insert/insertSection.php",
               {
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

<div id="insertsectionPage">
    <div class="title">
        Inserisci un nuovo reparto
    </div>
    <?php
    if (!isset($_POST['Nome']) || !isset($_POST['Desc'])) {
        ?>
        <div class="infoSection">
            Nome:<br><input id="name" name="Nome" type="text"><br><br>

            Descrizione:<br><textarea id="desc" name="Desc" cols="100" rows="20"></textarea><br><br>

            <button id="submit">Conferma</button>
        </div>
</div>
    <?php
    }
    else {
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

        $SQL = "INSERT INTO reparti(Nome, `Desc`) VALUES ('" . $conn->real_escape_string($Nome) . "', '" . $conn->real_escape_string($Desc) . "')";

        $result = $conn->query($SQL) or die($conn->error);

        if ($result) {
            echo "Reparto inserito con successo!";
            ?>
            <script type="text/javascript">
                setTimeout(function () {
                    homepage();
                }, 1500);
            </script>
            </div>
        <?php
        } else
            echo "Errore 1: " . mysql_error($conn) . "</div>";
    }
    ?>