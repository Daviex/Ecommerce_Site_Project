<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" http://www.w3.org/TR/html4/loose.dtd>
<html>
<head>  
    <?php
    include("../include/BasicLib.php");
    ?>
    <title> Il Covo - Amministrazione </title>
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
                    if($rankSession > 1)
                    {
                        ?>
                        <li class="right"><a href="../operator/index.php">Operatore</a></li>
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
                        <li><a href="#" onclick="insertProd()">Inserisci Prodotto</a></li>
                        <li><a href="#" onclick="insertCat()">Inserisci Categoria</a></li>
                        <li><a href="#" onclick="insertSec()">Inserisci Reparto</a></li>
                        <li><a href="#" onclick="insertState()">Inserisci Stato</a></li>
                        <li><a href="#" onclick="insertExpress()">Inserisci Corriere</a></li>
                        <br>
                        <li><a href="#" onclick="manageProd()">Gestione Prodotto</a></li>
                        <li><a href="#" onclick="manageCat()">Gestione Categoria</a></li>
                        <li><a href="#" onclick="manageSec()">Gestione Reparto</a></li>
                        <li><a href="#" onclick="manageUserRank()">Gestione Rank Utenti</a></li>
                        <li><a href="#" onclick="manageState()">Gestione Stato</a></li>
                        <li><a href="#" onclick="manageExpress()">Gestione Corriere</a></li>
                        <br>
                        <li><a href="#" onclick="deleteProd()">Elimina Prodotto</a></li>
                        <li><a href="#" onclick="deleteCat()">Elimina Categoria</a></li>
                        <li><a href="#" onclick="deleteSec()">Elimina Reparto</a></li>
                        <li><a href="#" onclick="deleteState()">Elimina Stato</a></li>
                        <li><a href="#" onclick="deleteExpress()">Elimina Corriere</a></li>
                    <?php
                    }
                    ?>
                </ul>
            <br>
            </div>
            <div class="flex-greater flex-Section">
                <div id="section">
                    <?php
                    if($rankSession < 3)
                    {
                        echo "Non dovresti essere qui, meglio riportarti nel posto giusto...";
                        ?>
                        <script type="text/javascript">
                            setTimeout(function(){ window.location.href = "../index.php"; }, 1500);
                        </script>
                        <?php
                    }
                    elseif(isset($_SESSION['loadPage']))
                    {
                        ?>
                        <script type="text/javascript">
                            <?php echo $_SESSION['loadPage'];?>();
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