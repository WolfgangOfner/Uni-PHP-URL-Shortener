<form method="post" accept-charset="UTF-8">
    <div class="label">
        <br />
        <label>Email:*</label><br />
        <input id="email" type="email" name="email" required />
        <br />
        <label>Benutzername:*</label><br />
        <input id="user" name="user" required />
        <br />
        <label>Passwort:*</label><br />
        <input id="password" name="password" type="password" pattern=".{6,}" required title="6 Zeichen mindestens" />
        <br />
        <label>Passwort wiederholen:*</label><br />
        <input id="passwordRep" name="passwordRep" type="password" pattern=".{6,}" required title="6 Zeichen mindestens" />
        <br />
        <label>Vorname:</label><br />
        <input id="firstName" name="firstName" />
        <br />
        <label>Nachname:</label><br />
        <input id="lastName" name="lastName" />
        <br />
        <label>Adresse:</label><br />
        <input id="adress" name="adress" />
        <br />
        <label>Geburtstag:</label><br />
        <input id="birthday" name="birthday" readonly>
        <br />
        <input type="submit" id="btnReg" value="Registrieren" />
        <br />
        <label>* sind Pflichtfelder</label>
        <br />
    </div>
</form>

<?php

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $message["username"] = $_POST["user"];
    $message["password"] = $_POST["password"];
    $passwordRep = $_POST["passwordRep"];
    $message["email"] = $_POST["email"];
    $message["firstName"] = $_POST["firstName"];
    $message["lastName"] = $_POST["lastName"];
    $message["adress"] = $_POST["adress"];
    $message["birthday"] = $_POST["birthday"];

    if ($message["password"] == $passwordRep)
    {
        require("/database/dbservice.php");
        $dbservice = new dbservice();
        
        $dbservice->AddDBEntry($message);
    }   
    else
    {        
        echo "<label class=labelRed>Passwörter stimmen nicht überein</label><br />";
    }    
}
?>