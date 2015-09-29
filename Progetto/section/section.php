<link rel="stylesheet" href="./asset/styles/sections.css" type="text/css">

<?php
include("../include/BasicLib.php");

if(isset($_GET['idSect']))
    $sectionID = $_GET['idSect'];
else
    $sectionID = -1;

$SQL = "SELECT * FROM reparti WHERE ID = '$sectionID'";

$result = $conn->query($SQL);

if($result->num_rows > 0)
{
    $row = $result->fetch_assoc();

    $currentSectionName = $row['Nome'];
    $currentSectionDescription = $row['Desc'];

//TODO: PERFECT COUNT FOR INFOPRODUCT IS ABOUT 378 Chars, where the last three are dots!
?>

<div id="sectionsPage">
    <div class="title">
        Categorie disponili per <?php echo $currentSectionName; ?>
    </div><br><br><br>
    <div class="description">
        <?php
            echo $currentSectionDescription;
        ?>
    </div>
    <div style="clear: both;"></div>

    <?php
    $SQL = "SELECT
          rep.ID AS repID,
          rep.Nome AS repNome,
          rep.Desc AS repDesc,
          cat.ID AS catID,
          cat.Nome AS catNome,
          cat.Desc AS catDesc,
          cat.Icon AS catIcon
        FROM ( reparti AS rep LEFT JOIN categorie as cat
          ON rep.ID = cat.IDRep )
        WHERE cat.IDRep ='$sectionID'";

    $result = $conn->query($SQL);

    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc()) {
            $repName = $row['repNome'];
            $catID = $row['catID'];
            $catName = $row['catNome'];
            $catDesc = $row['catDesc'];
            $catIcon = $row['catIcon'];
            ?>
            <div class="separator">
                <div class="image">
                    <?php
                        echo "<img src=\"section/images/".strtolower($repName)."/".$catIcon."\" width=\"120px\" height=\"120px\">";
                    ?>
                </div>
                <div class="categoryName">
                    <?php
                        echo "<a href=\"#\" onclick=\"category(".$catID.")\">".$catName."</a><br>";
                    ?>
                </div>
                <div class="infoCategory">
                    <?php
                        if(strlen($catDesc) >= 378)
                            $catDesc = substr($catDesc, 0, -3)."...";
                        echo "$catDesc";
                    ?>
                </div>

                <!-- Questo mi imposterà per bene la round box attorno ai float! -->
                <div style="clear: both;"></div>
            </div>
            <br>
        <?php
        }
    }
    ?>

    <!-- Questo mi imposterà per bene la round box attorno ai float! -->
    <div style="clear: both;"></div>
</div>

<?php
}
else
{
    echo "C'è stato un errore... Verrai rimandato alla home!";
    ?>
    <script type="text/javascript">
        setTimeout(function () {
            window.location.href = "index.php";
        }, 1500);
    </script>
    <?php
}
$conn->close();
?>