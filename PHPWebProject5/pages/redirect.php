<?php
	require("../database/dbservice.php");
    $dbservice = new dbservice();

    $dbservice->Redirect($_GET["key"]);                     // key contains short url code
?>