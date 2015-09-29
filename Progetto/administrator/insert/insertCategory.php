<link rel="stylesheet" href="asset/styles/insertCategory.css" type="text/css">

<?php
include("../../include/BasicLib.php");
if(isset($_POST['IDRep']))
{
    ?>
    <script src="../asset/js/jquery-2.1.3.js"></script>
<?php
}
?>
<?php
if(isset($_POST['IDRep'])) {

    //Stores the filename as it was on the client computer.
    $imagename = $_FILES['File']['name'];
    //Stores the filetype e.g image/jpeg
    $imagetype = $_FILES['File']['type'];
    //Stores any error codes from the upload.
    $imageerror = $_FILES['File']['error'];
    //Stores the tempname as it is given by the host when uploaded.
    $imagetemp = $_FILES['File']['tmp_name'];

    //The path you wish to upload the image to
    $IDRep = $_POST['IDRep'];
    $SQL = "SELECT Nome FROM reparti WHERE ID = '$IDRep'";
    $row = $conn->query($SQL)->fetch_assoc();
    $imagePath = "../../section/images/".strtolower($row['Nome'])."/";

    if(!file_exists($imagePath))
        mkdir($imagePath);

    if(is_uploaded_file($imagetemp)) {
        if(move_uploaded_file($imagetemp, $imagePath . $imagename)) {
            echo "Done";
        }
        else {
            echo "Failed to move your image.";
        }
    }
    else {
        echo "Failed to upload your image.";
    }

    $_SESSION['Image'] = $imagename;
    $_SESSION['Nome'] = $_POST['Nome'];
    $_SESSION['Desc'] = $_POST['Desc'];
    $_SESSION['IDRep'] = $_POST['IDRep'];
    $_SESSION['loadPage'] = "insertCat";

    ?>
    <script type="text/javascript">
        window.location.href = "../index.php";
    </script>
<?php

}
else {
    ?>
    <div id="insertcategoryPage">
        <div class="title">
            Inserisci una nuova categoria
        </div>
        <?php
        if (!isset($_POST['IDRep']) && !isset($_SESSION['loadPage'])) {
            ?>
            <div class="infoCategory">
                <?php
                $SQL = "SELECT * FROM reparti";
                $result = $conn->query($SQL);
                ?>
                <form method="POST" action="insert/insertCategory.php" enctype="multipart/form-data">
                    <!-- Automatically load categories when chosed section -->
                    <select name="IDRep">
                    <option>Seleziona un reparto</option>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["ID"] . "'>" . $row['Nome'] . "</option>";
                        }
                        ?>
                     </select><br><br>

                    Nome:<br><input id="name" name="Nome" type="text"><br><br>

                    Descrizione:<br><textarea id="desc" name="Desc" cols="100" rows="20"></textarea><br><br>

                    Scegli un immagine:<br><input type="file" name="File" id="File">
                    <br>
                    <br>
                    <button id="submit">Conferma</button>
                </form>
            </div>
        <?php
        }
        elseif(isset($_SESSION['loadPage']))
        {
            if($_SESSION['loadPage'] == "insertCat")
            {
                $Image = $_SESSION['Image'];
                $Nome = $_SESSION['Nome'];
                $Desc = $_SESSION['Desc'];
                $IDRep = $_SESSION['IDRep'];

                unset($_SESSION['Nome']);
                unset($_SESSION['Desc']);
                unset($_SESSION['Image']);
                unset($_SESSION['IDRep']);
                unset($_SESSION['loadPage']);

                if(!isset($Image))
                {
                    echo "Errore upload file!";
                    return;
                }

                if($Nome == "")
                {
                    echo "Il nome della categoria è vuota, riprova!";
                    return;
                }

                if($Desc == "")
                {
                    echo "La descrizione della categoria è vuota, riprova!";
                    return;
                }

                $SQL = "INSERT INTO categorie(Nome, `Desc`, Icon, IDRep) VALUES ('".$conn->real_escape_string($Nome)."', '".$conn->real_escape_string($Desc)."', '".$conn->real_escape_string($Image)."', '$IDRep')";

                $result = $conn->query($SQL) or die($conn->error);

                if($result) {
                    echo "Categoria inserita con successo!";
                    ?>
                        <script type="text/javascript">
                            setTimeout(function () {
                                homepage();
                            }, 1500);
                        </script>
                    <?php
                }
                else
                    echo "Errore 1: ".mysql_error($conn);
            }
        }
        ?>
    </div>
<?php
}
?>