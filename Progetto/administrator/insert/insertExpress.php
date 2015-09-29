<link rel="stylesheet" href="asset/styles/insertExpress.css" type="text/css">

<script type="text/javascript">
    $(document).ready(function() {
       $("#submit").click(function() {
           var Nome = $("#name").val();
           var Costo = $("#price").val();
           var Tempo = $("#tempo").val();

           $.post(
               "insert/insertExpress.php",
               {
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

<div id="insertexpressPage">
    <div class="title">
        Inserisci un nuovo corriere
    </div>
    <?php
    if (!isset($_POST['Nome'])) {
        ?>
        <div class="infoExpress">
            Nome:<br><input id="name" name="Nome" type="text"><br><br>
            Costo:<br><input type="number" id="price" name="Price" min="0.01" step="0.01" max="2500" value="1"> Euro<br><br>
            Tempo di Consegna:<br><input type="text" id="tempo" name="Tempo"><br><br>

            <button id="submit">Conferma</button>
        </div>
</div>
    <?php
    }
    else {
        $Nome = $_POST['Nome'];
        $Costo = (int)$_POST['Costo'];
        $Tempo = $_POST['Tempo'];

        if ($Nome == "") {
            echo "Il nome del corriere è vuoto, riprova!";
            return;
        }
        if ($Costo == "" && !is_numeric($Costo)) {
            echo "Il costo del corriere è vuoto, riprova!";
            return;
        }
        if ($Tempo == "") {
            echo "Il tempo di consegna del corriere è vuoto, riprova!";
            return;
        }

        $SQL = "INSERT INTO corrieri(Nominativo, Costo, Tempi_Consegna) VALUES ('" . $conn->real_escape_string($Nome) . "', '" . $Costo . "', '" . $conn->real_escape_string($Tempo) . "')";

        $result = $conn->query($SQL) or die($conn->error);

        if ($result) {
            echo "Corriere inserito con successo!";
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