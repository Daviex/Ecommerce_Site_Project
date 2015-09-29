<link rel="stylesheet" href="./asset/styles/profile.css" type="text/css">

<?php
include("../include/BasicLib.php");
?>

<h1>Profilo di <?php echo $usernameSession; ?></h1>

<?php
if($logged)
{        
	?>
	<div id="myprofilePage">
        <div class="roundedBox">
            <div class="links h2">
                <a href="#" onclick="myCart()">Carrello Prodotti</a>
            </div>

			<div class="links h2">
				<a href="#" onclick="myOrders()">Ordini Effettuati</a>
			</div>
		</div>
	</div>
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