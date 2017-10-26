<form method="get" class="label" accept-charset="UTF-8">
    <br />
    <label>Benutzername oder Email:</label><br />
    <input id="user" name="user" required />
    <br />
    <label>Passwort:</label><br />
    <input id="password" type="password" name="password" required />
    <br />
    <input type="button" id="btnLogin" value="Login" />
    <br />
</form>

<?php

if($_SERVER["REQUEST_METHOD"] == "POST")                                                    // try to login user
{
    $message ["username"] = $_POST["user"];
    $message ["password"] = $_POST["password"];
    
    require("/database/dbservice.php");
        $dbservice = new dbservice();

  $dbservice->DbLogin($message); 
}

?>