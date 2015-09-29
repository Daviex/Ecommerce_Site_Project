<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" http://www.w3.org/TR/html4/loose.dtd>
<html>
<head>  
    <?php
    include("../include/BasicLib.php");
    ?>
    <title> Il Covo - Operatore </title>
    <meta charset="utf-8">

    <script src="asset/js/jquery-2.1.3.js"></script>
    <script src="asset/js/redirects.js"></script>

    <script type="text/javascript">
    </script>

    <link rel="stylesheet" href="asset/styles/general.css" type="text/css">
</head>
<body>
    <div class="header">
        <ul class="navMenu">   
            <li class="left logo"><span class="logo" onclick="homepage()">Il Covo</span></li>
            
            <?php
                if($logged)
                {
                    if($rankSession > 2)
                    {
                        ?>
                        <li class="right"><a href="../administrator/index.php">Admin</a></li>
                        <li class="right"> | </li>
                        <?php
                    }
                    ?>
                    <li class="right"><a href="../index.php">Home</a></li>
                    <li class="right"> | </li>
                    <?php
            ?>
                <li class="right"><a href="#" onclick="logout()" >Log Out</a></li>
                <li class="right"> | </li>
                <li class="right"><a href='#' onclick="myProfile()">Benvenuto, <?php echo $usernameSession; ?></a></li>
            <?php
                }
            ?>
        </ul>        
    </div> 
    <div class="section">
        <div class="container">
            <div class="flex-equal flex-navBar navbar">
                <ul>
                    <?php
                    if($rankSession >= 2) {
                        ?>
                        <li><a href="#" onclick="">Home</a></li>
                        <br>
                        <li><a href="#" onclick="manageOrders()">Gestisci Ordini</a></li>
                        <li><a href="#" onclick="manageDiscounts()">Gestisci Sconti</a></li>
                        <li><a href="#" onclick="updateQuantity()">Aggiorna Quantità</a></li>
                        <br>
                    <?php
                    }
                    ?>
                </ul>
            <br>
            </div>
            <div class="flex-greater flex-Section">
                <div id="section">
                    <?php
                    if($rankSession < 2)
                    {
                        echo "Non dovresti essere qui, meglio riportarti nel posto giusto...";
                        ?>
                        <script type="text/javascript">
                            setTimeout(function(){ window.location.href = "../index.php"; }, 1500);
                        </script>
                        <?php
                    }
                    else {
                    ?>
                    <script type="text/javascript">
                        homepage();
                    </script>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>    
</body>
</html>