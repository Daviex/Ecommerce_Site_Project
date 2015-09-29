<link rel="stylesheet" href="asset/styles/registration.css" type="text/css">

<h1>Registrati</h1>

<script type="text/javascript">
    $(document).ready(function() {
      $("#submit").click(function(){
          var username = $("#username").val();
          var password = $("#password").val();
          var email = $("#email").val();
          var nome = $("#nome").val();
          var cognome = $("#cognome").val();
          var citta = $("#citta").val();
          var indirizzo = $("#indirizzo").val();
          var cap = $("#CAP").val();
          var num_tel = $("#num_tel").val();
          var data_nasc = $("#data_nasc").val();
          var cod_fisc = $("#cod_fisc").val();

        $.post(
            "registration.php",
            { 
                username: username,
                password: password,
                email: email,
                nome: nome, 
                cognome: cognome,
                citta: citta,
                indirizzo: indirizzo,
                cap: cap,
                num_tel: num_tel,
                data_nasc: data_nasc,
                cod_fisc: cod_fisc
            }, 
            function(msg)
            {
                loadPageWithFXAjax(msg);
            });
    });
  });
</script>     

<?php 
include("include/BasicLib.php");

if(!$logged)
{
    if(@checkParameter($_POST['username']) && @checkParameter($_POST['password']) && @checkParameter($_POST['email']) && @checkParameter($_POST['nome']) && @checkParameter($_POST['cognome']) && @checkParameter($_POST['citta']) && @checkParameter($_POST['indirizzo']) && @checkParameter($_POST['cap']) && @checkParameter($_POST['num_tel']) && @checkParameter($_POST['data_nasc']))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $citta = $_POST['citta'];
        $indirizzo = $_POST['indirizzo'];
        $cap = (int)$_POST['cap'];
        $num_tel = $_POST['num_tel'];
        $data_nasc = $_POST['data_nasc'];
        $cod_fisc = $_POST['cod_fisc'];

        if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $username))
        {                    
            echo "L'username non può avere caratteri speciali, riprova!";
            return;
        }
        
        if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password))
        {                    
            echo "La password non può avere caratteri speciali, riprova!";
            return;
        }
        
        $SQL = "SELECT * FROM utenti WHERE Username = '".$username."'";
        
        $result = $conn->query($SQL);
        
        if($result->num_rows > 0)
        {
            echo "Username già utilizzato, riprova!";
            return;
        }
        
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            echo "Email non valida, prego riprovare!";
            return;
        }
        
        if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $nome))
        {                    
            echo "Il tuo nome non può avere caratteri speciali, riprova!";
            return;
        }
        
        if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $cognome))
        {                    
            echo "Il tuo cognome non può avere caratteri speciali, riprova!";
            return;
        }
        
        if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $indirizzo))
        {                    
            echo "Il tuo indirizzo non può avere caratteri speciali, riprova!";
            return;
        }

        if(!is_numeric($cap))
        {
            echo "Il tuo CAP dev'essere numerico, riprova!";
            return;
        }
        
        if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $citta))
        {                    
            echo "La città non può avere caratteri speciali, riprova!";
            return;
        }
        
        if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $num_tel))
        {                    
            echo "Il tuo numero di telefono non può avere caratteri speciali, riprova!";
            return;
        }

        if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $cod_fisc))
        {
            echo "Il codice fiscale non può avere caratteri speciali, riprova!";
            return;
        }

        
        $SQL = "INSERT INTO `utenti` (`Nome`, `Cognome`, `Citta`, `Indirizzo`, `CAP`, `Num_Tel`, `Data_Nasc`, `Cod_Fisc`, `Username`, `Password`, `Email`, `Rank`) VALUES ('".mysql_real_escape_string($nome)."', '".mysql_real_escape_string($cognome)."', '".$citta."', '".$indirizzo."', '".$cap."', '".$num_tel."', '".$data_nasc."', '".$cod_fisc."', '".mysql_real_escape_string($username)."', '".mysql_real_escape_string($password)."', '".mysql_real_escape_string($email)."', '1')";
        
        $result = $conn->query($SQL);
        
        if($result)
            echo "Registrazione effettuata con successo!";
        else
            echo "Errore 1: ".mysql_error($conn);
        
        ?>        
        <br>
        <br>
        <button onclick='window.location.replace("index.php")'>Torna alla Home</button>  
        <?php
    }
    else
    {
        if(count($_POST) > 0)
        {
            echo "Uno o più campi sono vuoti, riprovare!";
        }
        ?>
        <form name="Registration">
            <div id="registrationForm">
                <div class="registration">
                    <span class="h3Registration">Username</span> &nbsp; &nbsp;
                    <input type="text" id="username" name="username" pattern=".{3,10}" placeholder="Minimo 3, Massimo 10">
                </div>
                <div class="registration">
                    <span class="h3Registration">Password</span> &nbsp; &nbsp;
                    <input type="password" id="password" name="password" pattern=".{6,16}" placeholder="Minimo 6, Massimo 16">
                </div>
                <div class="registration">
                    <span class="h3Registration">E-Mail</span> &nbsp; &nbsp;
                    <input type="email" id="email" name="email" placeholder="email@domain.ext">
                </div>
                <div class="registration">
                    <span class="h3Registration">Nome</span> &nbsp; &nbsp;
                    <input type="text" id="nome" name="nome">
                </div>
                <div class="registration">
                    <span class="h3Registration">Cognome</span> &nbsp; &nbsp;
                    <input type="text" id="cognome" name="cognome">
                </div>
                <div class="registration">
                    <span class="h3Registration">Città</span> &nbsp; &nbsp;
                    <input type="text" id="citta" name="citta" placeholder="Nome Città">
                </div>
                <div class="registration">
                    <span class="h3Registration">Indirizzo</span> &nbsp; &nbsp;
                    <input type="text" id="indirizzo" name="indirizzo">
                </div>
                <div class="registration">
                    <span class="h3Registration">CAP</span> &nbsp; &nbsp;
                    <input type="text" pattern=".{5, 5}" id="CAP" name="CAP">
                </div>
                <div class="registration">
                    <span class="h3Registration">Numero di Telefono</span> &nbsp; &nbsp;
                    <input type="text" id="num_tel" name="num_tel">
                </div>
                <div class="registration">
                    <span class="h3Registration">Data di Nascita</span> &nbsp; &nbsp;  
                    <input type="date" id="data_nasc" name="data_nasc">
                </div>
                <div class="registration">
                    <span class="h3Registration">Codice Fiscale</span> &nbsp; &nbsp;
                    <input type="text" id="cod_fisc" name="cod_fisc" pattern=".{16,16}">
                </div>
                <br>            
                <br>
                <input class="buttonStyle" type="button" id="submit" value="Conferma"> 
            </div>
        </form>
        <?php
    }
}
else
    echo "Sei loggato, per registrarti, effettua il log out!";

$conn->close();
?>