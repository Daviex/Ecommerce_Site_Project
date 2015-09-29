<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" http://www.w3.org/TR/html4/loose.dtd>
<html>
<head>  
    <?php
        include("include/BasicLib.php");
    ?>
    <title> Il Covo </title>
    <meta charset="utf-8">

    <script src="asset/js/jquery-2.1.3.js"></script>
    <script src="asset/js/redirects.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#searchBarButton").click(function () {
                var SearchText = $("#searchBar").val();

                $.post(
                    "search.php",
                    {
                        SearchText: SearchText
                    },
                    function(msg) {
                        loadPageWithFXAjax(msg);
                    }
                );
            });
        });

        $(document).ready(function() {
            $("#searchBar").keypress(function(event){
                if(event.keyCode == 13) {
                    $('#searchBarButton').click();
                }
            });
        });
    </script>

    <link rel="stylesheet" href="asset/styles/general.css" type="text/css">
</head>
<body>
    <div class="header">
        <ul class="navMenu">   
            <li class="left logo"><span class="logo" onclick="homepage()">Il Covo</span></li>
            <li class="left search">Ricerca: <input type="text" id="searchBar"><button id='searchBarButton' class="search"></button></li>
            
            <?php
                if($logged)
                {
                    if($rankSession > 1)
                    {
                        ?>
                        <li class="right"><a href="operator/index.php">Operatore</a></li>
                        <li class="right"> | </li>
                        <?php
                    }
                    if($rankSession > 2)
                    {
                        ?>
                        <li class="right"><a href="administrator/index.php">Admin</a></li>
                        <li class="right"> | </li>
                        <?php
                    }
            ?>
                <li class="right"><a href="#" onclick="logout()" >Log Out</a></li>
                <li class="right"> | </li>
                <li class="right"><a href='#' onclick="myProfile()">Benvenuto, <?php echo $usernameSession; ?></a></li>
            <?php
                }
                else
                {
            ?>
                <li class="right"><a href="#" onclick="registration()">Registrati</a></li>
                <li class="right"> | </li>
                <li class="right"><a href="#" onclick="login()">Log In</a></li>
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
                    $SQL = "SELECT * FROM reparti ORDER BY Nome";

                    $result = $conn->query($SQL);

                    if($result->num_rows > 0)
                    {
                        while($row = $result->fetch_assoc())
                        {
                            echo "<li>";

                            echo "<a href=\"#\" onclick=\"section(".$row['ID'].")\" title=\"".$row['Desc']."\">".$row['Nome']."</a>";

                            echo "</li>";
                        }
                    }
                    ?>
                </ul>
            <br>
            </div>
            <div class="flex-greater flex-Section">
                <div id="section">
                    <script type="text/javascript">
                        homepage();
                    </script>                    
                </div>
            </div>
        </div>
    </div>    
</body>
</html>