<form method="post" accept-charset="UTF-8">
    <div class="label">
        <br />
        <label>Email:</label><br />
        <input id="email" type="email" name="email" />
        <label>&nbsp;aktuelle Email: <?= $_SESSION['email'] ?></label>
        <br />
        <label>Benutzername:</label><br />
        <input id="user" name="user" />
        <label>&nbsp;aktueller Benutzername: <?= $_SESSION['user'] ?></label>
        <br />
        <label>neues Passwort:</label><br />
        <input id="password" name="password" type="password" pattern=".{6,}" title="6 Zeichen mindestens" />
        <br />
        <label>Vorname:</label><br />
        <input id="firstName" name="firstName" value="<?= htmlspecialchars($_SESSION['firstName']) ?>" />
        <br />
        <label>Nachname:</label><br />
        <input id="lastName" name="lastName" value="<?= htmlspecialchars($_SESSION['lastName']) ?>" />
        <br />
        <label>Adresse:</label><br />
        <input id="adress" name="adress" value="<?= htmlspecialchars($_SESSION['adress']) ?>" />
        <br />
        <label>Geburtstag:</label><br />
        <input id="birthday" name="birthday" value="<?= htmlspecialchars($_SESSION['birthday']) ?>" readonly>
        <br />
        <label>altes Passwort*</label><br />
        <input id="passwordOld" name="passwordOld" type="password" pattern=".{6,}" required title="6 Zeichen mindestens" />
        <br />
        <input type="submit" id="btnReg" name="btnReg" value="Profil Aktualisieren" />
        <input type="submit" id="btnDelete" name="btnDelete" value="Profil löschen" />
        <br />
        <label>* sind Pflichtfelder</label>
        <br />
    </div>
</form>

<?php

if($_SERVER["REQUEST_METHOD"] == "POST")           
{
    $message["usernameOld"] = $_SESSION["user"];
    $message["username"] = $_POST["user"];
    $message["password"] = $_POST["password"];
    $message["passwordOld"] = $_POST["passwordOld"];
    $message["email"] = $_POST["email"];
    $message["firstName"] = $_POST["firstName"];
    $message["lastName"] = $_POST["lastName"];
    $message["adress"] = $_POST["adress"];
    $message["birthday"] = $_POST["birthday"];

    require("/database/dbservice.php");
    $dbservice = new dbservice();
    
    if (isset($_POST["btnReg"]))                                                            // update profil
    {
        $dbservice->UpdateProfil($message);
    }
    else                                                                                    // delete profil
    {
        $dbservice->DeleteProfil($message);
    }
}
?>