<?php
$logged = false;
$idSession = -1;
$usernameSession = "";
$passwordSession = "";
$rankSession = -1;

$root = $_SERVER['DOCUMENT_ROOT']."/progetto";

if(@isset($_SESSION['login']))
{
    $values = explode(',', $_SESSION['login']);
    
    $SQL = "SELECT * FROM utenti WHERE ID = '$values[2]'";
    
    $result = $conn->query($SQL);
    
    $row = $result->fetch_assoc();
    
    $idSession = $row['ID'];
    $usernameSession = $row['Username'];
    $passwordSession = md5($row['Password']);
    $rankSession = $row['Rank'];
    
    if(strtolower($usernameSession) == strtolower($values[0]) && $passwordSession == $values[1] && $idSession == $values[2])
    {
        $logged = true;
    }            
}

if(!isset($_SESSION['cart']))
{
    /*
     * Example of syntax :
     * {\"cart\":[{\"IDProd\":0,\"Quantita\":1}]}
    */
    $_SESSION['cart'] = "{\"cart\":[]}";
}

$_SESSION['logged'] = $logged;
?>