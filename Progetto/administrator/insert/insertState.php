<link rel="stylesheet" href="asset/styles/insertState.css" type="text/css">

<script type="text/javascript">
    $(document).ready(function() {
       $("#submit").click(function() {
           var Nome = $("#name").val();

           $.post(
               "insert/insertState.php",
               {
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

<div id="insertstatePage">
    <div class="title">
        Inserisci un nuovo stato per gli ordini
    </div>
    <?php
    if (!isset($_POST['Nome'])) {
        ?>
        <div class="infoState">
            Nome:<br><input id="name" name="Nome" type="text"><br><br>

            <button id="submit">Conferma</button>
        </div>
</div>
    <?php
    }
    else {
        $Nome = $_POST['Nome'];

        if ($Nome == "") {
            echo "Il nome della categoria è vuota, riprova!";
            return;
        }

        $SQL = "INSERT INTO stati(Nome) VALUES ('" . $conn->real_escape_string($Nome) . "')";

        $result = $conn->query($SQL) or die($conn->error);

        if ($result) {
            echo "Stato inserito con successo!";
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