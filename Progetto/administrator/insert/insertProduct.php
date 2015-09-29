<link rel="stylesheet" href="asset/styles/insertProduct.css" type="text/css">

<?php
include("../../include/BasicLib.php");
if(isset($_POST['IDCat']) && !isset($_POST['Image']))
{
    ?>
    <script src="../asset/js/jquery-2.1.3.js"></script>
    <?php
}
?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#departments").change(function(){
            var IDRep = $("#departments").val();
            if(IDRep > 0) {
                $.post(
                    "insert/insertProduct.php",
                    {
                        IDRep: IDRep
                    },
                    function (msg) {
                        $("#category").html(msg);
                    }
                );
            }
        });
    });
</script>
<?php
if(isset($_POST['IDRep']) && !isset($_POST['IDCat'])) {
    $IDSec = $_POST['IDRep'];
    $SQL="SELECT * FROM categorie WHERE IDRep = '$IDSec'";
    echo $SQL;
    $result = $conn->query($SQL);
    $resultOptions = "<option value='0'>Seleziona una categoria</option>";
    while($row = $result->fetch_assoc()) {
        $resultOptions .= "<option value='".$row["ID"]."'>".$row['Nome']."</option>";
    }
    echo $resultOptions;
}
elseif(!isset($_POST['IDRep']) && isset($_POST['IDCat']) && !isset($_POST['Image'])) {

    //Stores the filename as it was on the client computer.
    $imagename = $_FILES['File']['name'];
    //Stores the filetype e.g image/jpeg
    $imagetype = $_FILES['File']['type'];
    //Stores any error codes from the upload.
    $imageerror = $_FILES['File']['error'];
    //Stores the tempname as it is given by the host when uploaded.
    $imagetemp = $_FILES['File']['tmp_name'];

    //The path you wish to upload the image to
    $IDCat = $_POST['IDCat'];
    $SQL = "SELECT Nome FROM categorie WHERE ID = '$IDCat'";
    $row = $conn->query($SQL)->fetch_assoc();
    $imagePath = "../../products/images/".strtolower($row['Nome'])."/";

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
    $_SESSION['Price'] = $_POST['Price'];
    $_SESSION['Quantity'] = $_POST['Quantity'];
    $_SESSION['IDCat'] = $_POST['IDCat'];
    $_SESSION['loadPage'] = "insertProd";

    ?>
    <script type="text/javascript">
        window.location.href = "../index.php";
    </script>
    <?php

}
else {
    ?>
    <div id="insertproductPage">
        <div class="title">
            Inserisci un nuovo prodotto
        </div>
        <?php
        if (!isset($_POST['IDRep']) && !isset($_POST['IDCat']) && !isset($_SESSION['loadPage'])) {
            ?>
            <div class="infoProduct">
                <?php
                $SQL = "SELECT * FROM reparti";
                $result = $conn->query($SQL);
                ?>
                <select id="departments">
                    <option>Seleziona un reparto</option>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["ID"] . "'>" . $row['Nome'] . "</option>";
                    }
                    ?>
                </select><br><br>
                <form method="POST" action="insert/insertProduct.php" enctype="multipart/form-data">
                    <!-- Automatically load categories when chosed section -->
                    <select id="category" name="IDCat">Categorie
                        <option value='0'>Seleziona una categoria</option>
                    </select><br><br>

                    Nome:<br><input id="name" name="Nome" type="text"><br><br>

                    Descrizione:<br><textarea id="desc" name="Desc" cols="100" rows="20"></textarea><br><br>

                    Prezzo:<br><input type="number" id="price" name="Price" min="0.01" step="0.01" max="2500" value="1"> Euro<br><br>

                    Quantità:<br><input type="text" name="Quantity" id="quantity" value="1"><br><br>

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
            if($_SESSION['loadPage'] == "insertProd")
            {
                $Image = $_SESSION['Image'];
                $Nome = $_SESSION['Nome'];
                $Desc = $_SESSION['Desc'];
                $Price = $_SESSION['Price'];
                $Quantity = $_SESSION['Quantity'];
                $IDCat = $_SESSION['IDCat'];

                unset($_SESSION['Nome']);
                unset($_SESSION['Desc']);
                unset($_SESSION['Price']);
                unset($_SESSION['Quantity']);
                unset($_SESSION['Image']);
                unset($_SESSION['IDCat']);
                unset($_SESSION['loadPage']);

                if(!isset($Image))
                {
                    echo "Errore upload file!";
                    return;
                }

                if($Nome == "")
                {
                    echo "Il nome del prodotto è errato, riprova!";
                    return;
                }

                if(!is_numeric($Price))
                {
                    echo "Il prezzo dev'essere numerico!";
                    return;
                }

                if(!is_numeric($Quantity))
                {
                    echo "Il prezzo dev'essere numerico!";
                    return;
                }

                $SQL = "INSERT INTO prodotti(Nome, `Desc`, Prezzo, Quantita, IMG, IDCat) VALUES ('".$conn->real_escape_string($Nome)."', '".$conn->real_escape_string($Desc)."', '$Price', '$Quantity', '".$conn->real_escape_string($Image)."', '$IDCat')";

                $result = $conn->query($SQL) or die($conn->error);

                if($result) {
                    echo "Prodotto inserito con successo!";
                    ?>
                        <script type="text/javascript">
                            setTimeout(function () {
                                window.location.href = "../index.php";
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