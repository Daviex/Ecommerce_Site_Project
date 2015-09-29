<link rel="stylesheet" href="asset/styles/manageProduct.css" type="text/css">

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
    function manageSingleProduct(IDProd)
    {
        $.post(
            "manage/manageProduct.php",
            {
                IDProd: IDProd
            },
            function(msg) {
                loadPageWithFXAjax(msg);
            }

        );
    }

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
    $SQL = "SELECT * FROM categorie WHERE IDRep = '$IDSec'";
    echo $SQL;
    $result = $conn->query($SQL);
    $resultOptions = "<option value='0'>Seleziona una categoria</option>";
    while ($row = $result->fetch_assoc()) {
        $resultOptions .= "<option value='" . $row["ID"] . "'>" . $row['Nome'] . "</option>";
    }
    echo $resultOptions;
}
elseif(!isset($_POST['IDRep']) && isset($_POST['IDCat']) && !isset($_POST['Image'])) {

    if($_FILES['File']['error'] != UPLOAD_ERR_NO_FILE) {
        //Stores the filename as it was on the client computer.
        $imagename = $_FILES['File']['name'];
        //Stores the filetype e.g image/jpeg
        $imagetype = $_FILES['File']['type'];
        //Stores any error codes from the upload.
        $imageerror = $_FILES['File']['error'];
        //Stores the tempname as it is given by the host when uploaded.
        $imagetemp = $_FILES['File']['tmp_name'];

        $oldImageName = $_POST['OldImage'];

        //The path you wish to upload the image to
        $IDCat = $_POST['IDCat'];
        $SQL = "SELECT Nome FROM categorie WHERE ID = '$IDCat'";
        $row = $conn->query($SQL)->fetch_assoc();

        $imagePath = $root."/products/images/" . strtolower($row['Nome']) . "/";

        if(!file_exists($imagePath))
            mkdir($imagePath);

        if (is_uploaded_file($imagetemp)) {
            if (move_uploaded_file($imagetemp, $imagePath . $imagename)) {
                echo "Done";
            } else {
                echo "Failed to move your image.";
            }
        } else {
            echo "Failed to upload your image.";
        }

        $_SESSION['Image'] = $imagename;
    }
    $_SESSION['Nome'] = $_POST['Nome'];
    $_SESSION['Desc'] = $_POST['Desc'];
    $_SESSION['Price'] = $_POST['Prezzo'];
    $_SESSION['Quantity'] = $_POST['Quantita'];
    $_SESSION['IDProd'] = $_POST['IDProd'];
    $_SESSION['IDCat'] = $_POST['IDCat'];
    $_SESSION['loadPage'] = "manageProd";

    ?>
    <script type="text/javascript">
        window.location.href = "../index.php";
    </script>
<?php
}
else {
    ?>
    <div id="manageproductPage">
        <div class="title">
            Modifica un prodotto
        </div>

        <?php
        if (!isset($_POST['IDProd']) && !isset($_SESSION['loadPage'])) {
            ?>
            <div class="infoProduct">
                <table>
                    <tr>
                        <th>Reparto</th>
                        <th>Categoria</th>
                        <th>Nome</th>
                        <th>Prezzo</th>
                        <th>Quantità</th>
                    </tr>
                    <?php
                    $SQL = "SELECT prod.ID as prodID, rep.Nome AS repNome, cat.Nome AS catNome, prod.Nome AS prodNome, prod.Prezzo, prod.Quantita
                    FROM ( prodotti AS prod LEFT JOIN categorie AS cat ON prod.IDCat = cat.ID) LEFT JOIN reparti AS rep ON cat.IDRep = rep.ID
                    ORDER BY prod.ID";
                    $result = $conn->query($SQL);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr onclick=\"manageSingleProduct(" . $row['prodID'] . ")\">";
                            echo "<td>" . $row['repNome'] . "</td>";
                            echo "<td>" . $row['catNome'] . "</td>";
                            echo "<td>" . $row['prodNome'] . "</td>";
                            echo "<td>" . $row['Prezzo'] . " €</td>";
                            echo "<td>" . $row['Quantita'] . "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>
            </div>

        <?php
        } elseif (isset($_POST['IDProd']) && !isset($_SESSION['loadPage'])) {
            ?>
            <div class="infoproduct">
            <?php
            $IDProd = $_POST['IDProd'];
            $SQL = "SELECT prod.ID as prodID, rep.ID AS repID, cat.ID AS catID, prod.Nome AS prodNome, prod.Desc AS Descr, prod.Prezzo, prod.Quantita, prod.IMG AS Image
                    FROM ( prodotti AS prod LEFT JOIN categorie AS cat ON prod.IDCat = cat.ID) LEFT JOIN reparti AS rep ON cat.IDRep = rep.ID
                    WHERE prod.ID = '$IDProd'";

            $result = $conn->query($SQL);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $IDRep = $row['repID'];
                $IDCat = $row['catID'];
                $prodNome = $row['prodNome'];
                $prodDesc = $row['Descr'];
                $prodPrice = $row['Prezzo'];
                $prodQuantity = $row['Quantita'];
                $prodImage = $row['Image'];
                ?>
                <select id="departments">
                    <?php

                    $SQL = "SELECT * FROM reparti";
                    $result = $conn->query($SQL);
                    while ($row = $result->fetch_assoc()) {
                        if ($row['ID'] != $IDRep)
                            echo "<option value='" . $row["ID"] . "'>" . $row['Nome'] . "</option>";
                        else
                            echo "<option selected value='" . $row["ID"] . "'>" . $row['Nome'] . "</option>";
                    }
                    ?>
                </select><br><br>
                <form id="formFile" method="POST" action="manage/manageProduct.php" enctype="multipart/form-data">
                    <!-- Automatically load categories when chosed section -->
                    <select id="category" name="IDCat">Categorie
                        <?php

                        $SQL = "SELECT * FROM categorie WHERE IDRep = '$IDRep'";
                        $result = $conn->query($SQL);
                        while ($row = $result->fetch_assoc()) {
                            if ($row['ID'] != $IDCat)
                                echo "<option value='" . $row["ID"] . "'>" . $row['Nome'] . "</option>";
                            else
                                echo "<option selected value='" . $row["ID"] . "'>" . $row['Nome'] . "</option>";
                        }
                        ?>
                    </select><br><br>

                    Nome:<br><input id="name" name="Nome" value="<?php echo $prodNome; ?>" type="text"><br><br>

                    Descrizione:<br><textarea id="desc" name="Desc" cols="60" rows="16"><?php echo $prodDesc; ?></textarea><br><br>

                    Prezzo:<br><input type="number" id="price" name="Prezzo" value="<?php echo $prodPrice; ?>" min="0.01" step="0.01">
                    Euro<br><br>

                    Quantità:<br><input type="text" name="Quantita" value="<?php echo $prodQuantity; ?>" id="quantity"><br>

                    <input type="hidden" id="productID" name="IDProd" value="<?php echo $IDProd; ?>">
                    <input type="hidden" name="OldImage" value="<?php echo $prodImage; ?>">

                    Scegli un immagine:<br><input type="file" name="File" id="File">
                    <br>
                    <br>
                    <button id="submit">Conferma</button>
                </form>
                </div>
            <?php
            }
        } elseif(isset($_SESSION['loadPage'])) {
            if ($_SESSION['loadPage'] == "manageProd") {

                $IDProd = $_SESSION['IDProd'];

                $SQL = "SELECT prod.ID as prodID, cat.ID AS catID, prod.Nome AS prodNome, prod.Desc AS Descr, prod.Prezzo, prod.Quantita, prod.IMG AS Image
                        FROM prodotti AS prod LEFT JOIN categorie AS cat ON prod.IDCat = cat.ID
                        WHERE prod.ID = '$IDProd'";

                $result = $conn->query($SQL);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();

                    $oldIDCat = $row['catID'];
                    $oldprodNome = $row['prodNome'];
                    $oldprodDesc = $row['Descr'];
                    $oldPrezzo = $row['Prezzo'];
                    $oldQuantita = $row['Quantita'];
                    $oldImage = $row['Image'];

                    if(isset($_SESSION['Image']))
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
                    unset($_SESSION['IDCat']);
                    unset($_SESSION['IDProd']);
                    unset($_SESSION['loadPage']);

                    if (!isset($Image) && isset($_SESSION['Image'])) {
                        echo "Errore upload file!";
                        return;
                    }

                    if ($Nome == "") {
                        echo "Il nome del prodotto è errato, riprova!";
                        return;
                    }

                    if (!is_numeric($Price)) {
                        echo "Il prezzo dev'essere numerico!";
                        return;
                    }

                    if (!is_numeric($Quantity)) {
                        echo "Il prezzo dev'essere numerico!";
                        return;
                    }

                    $SQL = "";

                    if ($Nome != $oldprodNome)
                    {
                        $SQL .= "UPDATE prodotti SET Nome = '".$conn->real_escape_string($Nome)."' WHERE ID = '$IDProd'; ";
                    }

                    if ($Desc != $oldprodDesc)
                    {
                        $SQL .= "UPDATE prodotti SET `Desc` = '".$conn->real_escape_string($Desc)."' WHERE ID = '$IDProd';";
                    }

                    if ($Price != $oldPrezzo)
                    {
                        $SQL .= "UPDATE prodotti SET Prezzo = '".$conn->real_escape_string($Price)."' WHERE ID = '$IDProd'; ";
                    }

                    if ($Quantity != $oldQuantita)
                    {
                        $SQL .= "UPDATE prodotti SET Quantita = '".$conn->real_escape_string($Quantity)."' WHERE ID = '$IDProd'; ";
                    }

                    if ($IDCat != $oldIDCat)
                    {
                        $SQL .= "UPDATE prodotti SET IDCat = '".$conn->real_escape_string($IDCat)."' WHERE ID = '$IDProd'; ";


                        $oldCatName = $conn->query("SELECT Nome FROM categorie WHERE ID='$oldIDCat'")->fetch_assoc();
                        $newCatName = $conn->query("SELECT Nome FROM categorie WHERE ID='$IDCat'")->fetch_assoc();

                        if(!isset($_SESSION['Image']))
                        {
                            $ImageNameToChange = $oldImage;

                            $oldImagePath = $root . "/products/images/" . strtolower($oldCatName['Nome']) . "/" . $ImageNameToChange;
                            $newImagePath = $root . "/products/images/" . strtolower($newCatName['Nome']) . "/" . $ImageNameToChange;

                            rename($oldImagePath, $newImagePath);
                        }
                        else
                        {
                            $ImageNameToChange = $oldImage;

                            $oldImagePath = $root . "/products/images/" . strtolower($oldCatName['Nome']) . "/" . $ImageNameToChange;

                            unlink($oldImagePath);
                        }
                    }

                    if(isset($_SESSION['Image'])) {
                        if ($Image != $oldImage) {
                            $SQL .= "UPDATE prodotti SET IMG = '" . $conn->real_escape_string($Image) . "' WHERE ID = '$IDProd'; ";

                        }
                        unset($_SESSION['Image']);
                    }

                    if($SQL != "")
                    {
                        $result = $conn->multi_query($SQL) or die($conn->error);

                        if ($result) {
                        echo "Prodotto aggiornato con successo!";
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
                        echo "Prodotto non aggiornato";
                }
            }
        }
        ?>
    </div>
<?php
}
$conn->close();
?>