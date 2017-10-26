
<h2>Willkommen</h2><br />
Wilkommen auf der Startseite von Wolfgangs wunderbarer Linkshortener. Gekürzte Links von nicht eingeloggten Benutzern werden 24 Stunden gespeichert. Links von eingeloggten Benutzern bleiben unbegrenzt gespeichert. <br />
<br />

Geben Sie Ihren Link ein, den Sie kürzen möchten: <br />

<form method="post" accept-charset="UTF-8">
    <label for="url"> http:// </label><input id="url" name="url"/><br />
    <input type="submit" id="btnLink" value="kürzen"/>
    </form>

<?php

if($_SERVER["REQUEST_METHOD"] == "POST")                                                            // if button was klicked shorten url
{
    $url = $_POST["url"];

    if ($url)
    {
        require("/database/dbservice.php");
        $dbservice = new dbservice();

        $dbservice->ShortenUrl($url); 
    }    
}
?>
     