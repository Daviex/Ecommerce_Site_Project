<h1>Log out</h1>     
<?php
include("include/BasicLib.php");   
    
if($logged)
{
    session_unset();
    session_destroy();
    echo "Hai effettuato il log out... Fra poco verrai rimandato alla home!";
    ?>
    <script type="text/javascript">
        setTimeout(function(){ window.location.href = "index.php"; }, 1500);
    </script>
    <?php
}
else
{
    echo "Non sei loggato... Verrai rimandato alla home!";
    ?>
    <script type="text/javascript">
        setTimeout(function(){ homepage(); }, 1500);
    </script>
    <?php
}
$conn->close();
?>