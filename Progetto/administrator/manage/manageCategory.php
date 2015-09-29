<link rel="stylesheet" href="asset/styles/manageCategory.css" type="text/css">

<?php
include("../../include/BasicLib.php");
if(isset($_POST['IDRep']) && !isset($_POST['Image']))
{
    ?>
    <script src="../asset/js/jquery-2.1.3.js"></script>
<?php
}
?>

<script type="text/javascript">
    function manageSingleCategory(IDCat)
    {
        $.post(
            "manage/manageCategory.php",
            {
                IDCat: IDCat
            },
            function(msg) {
                loadPageWithFXAjax(msg);
            }

        );
    }
</script>
<?php
if(isset($_POST['IDRep']) && isset($_POST['IDCat']) && !isset($_SESSION['Image'])) {
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
        $IDRep = $_POST['IDRep'];
        $SQL = "SELECT Nome FROM reparti WHERE ID = '$IDRep'";
        $row = $conn->query($SQL)->fetch_assoc();

        $imagePath = $root."/section/images/" . strtolower($row['Nome']) . "/";

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
    $_SESSION['IDRep'] = $_POST['IDRep'];
    $_SESSION['IDCat'] = $_POST['IDCat'];
    $_SESSION['loadPage'] = "manageCat";

    ?>
    <script type="text/javascript">
        window.location.href = "../index.php";
    </script>
<?php
}
else {
    ?>
    <div id="managecategoryPage">
        <div class="title">
            Modifica una categoria
        </div>

        <?php
        if (!isset($_POST['IDCat']) && !isset($_SESSION['loadPage'])) {
            ?>
            <div class="infoCategory">
                <table>
                    <tr>
                        <th>Reparto</th>
                        <th>Categoria</th>
                    </tr>
                    <?php
                    $SQL = "SELECT rep.Nome AS repNome, cat.Nome AS catNome, cat.ID AS catID
                            FROM categorie AS cat LEFT JOIN reparti AS rep ON cat.IDRep = rep.ID
                            ORDER BY cat.ID";
                    $result = $conn->query($SQL);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr onclick=\"manageSingleCategory(" . $row['catID'] . ")\">";
                            echo "<td>" . $row['repNome'] . "</td>";
                            echo "<td>" . $row['catNome'] . "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>
            </div>

        <?php
        } elseif (isset($_POST['IDCat']) && !isset($_SESSION['loadPage'])) {
        ?>
        <div class="infoCategory">
            <?php
            $IDCat = $_POST['IDCat'];
            $SQL = "SELECT rep.ID AS repID, cat.ID AS catID, cat.Nome AS catNome, cat.Desc AS Descr, cat.Icon AS Image
                    FROM categorie AS cat LEFT JOIN reparti AS rep ON cat.IDRep = rep.ID
                    WHERE cat.ID = '$IDCat'";

            $result = $conn->query($SQL);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $IDRep = $row['repID'];
                $IDCat = $row['catID'];
                $catNome = $row['catNome'];
                $catDesc = $row['Descr'];
                $catImage = $row['Image'];
                ?>
                <form id="formFile" method="POST" action="manage/manageCategory.php" enctype="multipart/form-data">
                    <!-- Automatically load categories when chosed section -->

                    <select id="departments" name="IDRep">
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

                    Nome:<br><input id="name" name="Nome" value="<?php echo $catNome; ?>" type="text"><br><br>

                    Descrizione:<br><textarea id="desc" name="Desc" cols="60" rows="16"><?php echo $catDesc; ?></textarea><br><br>

                    <input type="hidden" id="categoryID" name="IDCat" value="<?php echo $IDCat; ?>">
                    <input type="hidden" name="OldImage" value="<?php echo $catImage; ?>">

                    Scegli un immagine:<br><input type="file" name="File" id="File">
                    <br>
                    <br>
                    <button id="submit">Conferma</button>
                </form>
                </div>
            <?php
            }
        } elseif(isset($_SESSION['loadPage'])) {
            if ($_SESSION['loadPage'] == "manageCat") {

                $IDCat = $_SESSION['IDCat'];

                $SQL = "SELECT cat.ID AS catID, cat.Nome AS Nome, cat.Desc AS Descr, cat.Icon AS Image, cat.IDRep AS repID
                        FROM categorie AS cat
                        WHERE cat.ID = '$IDCat'";

                $result = $conn->query($SQL);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();

                    $oldIDCat = $row['catID'];
                    $oldIDRep = $row['repID'];
                    $oldcatNome = $row['Nome'];
                    $oldcatDesc = $row['Descr'];
                    $oldImage = $row['Image'];

                    if(isset($_SESSION['Image']))
                    {
                        $Image = $_SESSION['Image'];
                    }

                    $Nome = $_SESSION['Nome'];
                    $Desc = $_SESSION['Desc'];
                    $IDRep = $_SESSION['IDRep'];

                    unset($_SESSION['Nome']);
                    unset($_SESSION['Desc']);
                    unset($_SESSION['IDCat']);
                    unset($_SESSION['IDRep']);
                    unset($_SESSION['loadPage']);

                    if (!isset($Image) && isset($_SESSION['Image'])) {
                        echo "Errore upload file!";
                        return;
                    }

                    if ($Nome == "") {
                        echo "Il nome del prodotto è errato, riprova!";
                        return;
                    }

                    $SQL = "";

                    if ($Nome != $oldcatNome)
                    {
                        $SQL .= "UPDATE categorie SET Nome = '".$conn->real_escape_string($Nome)."' WHERE ID = '$IDCat'; ";
                    }

                    if ($Desc != $oldcatDesc)
                    {
                        $SQL .= "UPDATE categorie SET `Desc` = '".$conn->real_escape_string($Desc)."' WHERE ID = '$IDCat';";
                    }

                    if ($IDRep != $oldIDRep)
                    {
                        $SQL .= "UPDATE categorie SET IDRep = '".$conn->real_escape_string($IDRep)."' WHERE ID = '$IDCat'; ";

                        $oldCatName = $conn->query("SELECT Nome FROM reparti WHERE ID='$oldIDRep'")->fetch_assoc();
                        $newCatName = $conn->query("SELECT Nome FROM reparti WHERE ID='$IDRep'")->fetch_assoc();

                        if(!isset($_SESSION['Image']))
                        {
                            $ImageNameToChange = $oldImage;

                            $oldImagePath = $root . "/section/images/" . strtolower($oldCatName['Nome']) . "/" . $ImageNameToChange;
                            $newImagePath = $root . "/section/images/" . strtolower($newCatName['Nome']) . "/" . $ImageNameToChange;

                            rename($oldImagePath, $newImagePath);
                        }
                        else
                        {
                            $ImageNameToChange = $oldImage;

                            $oldImagePath = $root . "/section/images/" . strtolower($oldCatName['Nome']) . "/" . $ImageNameToChange;

                            unlink($oldImagePath);
                        }
                    }

                    if(isset($_SESSION['Image'])) {
                        if ($Image != $oldImage)
                        {
                            $SQL .= "UPDATE categorie SET Icon = '".$conn->real_escape_string($Image)."' WHERE ID = '$IDCat'; ";
                        }
                        unset($_SESSION['Image']);
                     }

                    if($SQL != "")
                    {
                        $result = $conn->multi_query($SQL) or die($conn->error);

                        if ($result) {
                        echo "Categoria aggiornata con successo!";
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
                        echo "Categoria non aggiornata";
                }
            }
        }
        ?>
    </div>
<?php
}
$conn->close();
?>