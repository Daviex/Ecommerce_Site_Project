<?php
include("include/BasicLib.php");
?>
<h1>Log In</h1>

<script type="text/javascript">
    $(document).ready(function() {
      $("#submit").click(function(){
        var username = $("#username").val();
        var password = $("#password").val();
        $.post(
            "login.php",
            {
                username: username,
                password: password
            },
            function(msg)
            {
                loadPageWithFXAjax(msg);
            });
    });
  });

    $('#password').change(function(e) {
        $('#submit').click();
    });
</script>

<?php
if($logged)
{
    echo "Sei già loggato... Verrai reinderizzato alla home!";
    ?>
    <script type="text/javascript">
        setTimeout(function(){ homepage(); }, 1500);
    </script>
    <?php
}
elseif(@checkParameter($_POST['username']) && @checkParameter($_POST['password']))
{
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    $SQL = "SELECT * FROM utenti WHERE Username = '".$username."' AND Password = '".$password."'";

    $result = $conn->query($SQL);

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if ($password == $row['Password']) {
            SetSession($row['ID'], $username, $password);
            echo "Login effettuato... Verrai reinderizzato alla home!";
            ?>
            <script type="text/javascript">
                setTimeout(function () {
                    window.location.href = "index.php";
                }, 1500);
            </script>
        <?php
        }
        else
            echo "Username o Password errati!";
    }
    else
        echo "Username o Password errati!";
}
else
{
    ?>
    <form name="Login">

        <h3 class="center">Username</h3>
        <input type="text" id="username" name="username" pattern=".{3,10}" placeholder="Minimo 3, Massimo 10">

        <br>

        <h3 class="center">Password</h3>
        <input type="password" id="password" name="password" pattern=".{6,16}" placeholder="Minimo 6, Massimo 16">

        <br>
        <br>

        <input class="buttonStyle" type="button" id="submit" value="Login">

    </form>
    <?php
}
$conn->close();
?>