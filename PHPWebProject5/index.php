<?php

session_set_cookie_params(7200,"/");                    // session lifetime 2 hours
session_start();
session_regenerate_id(true); 

error_reporting(E_ALL);
ini_set('display_errors', 1);

function renderIndex()
{
    $template = array();

    $page = "home";

    if(isset($_GET["page"]))
    {
        $page = $_GET["page"];
    }

    switch($page)
    {
        case "home":
            $template["title"] = "Home";
            $template["content_path"] = "home.php";
            break;
        
        case "register":
            $template["title"] = "Registrierung";
            $template["content_path"] = "register.php";
            break;
        
        case "login":
            $template["title"] = "Login";
            $template["content_path"] = "login.php";
            break;
        
        case "logout":
            $template["title"] = "Logout";
            $template["content_path"] = "logout.php";
            break;
        
        case "welcome":
            $template["title"] = "Willkommen";
            $template["content_path"] = "welcome.php";
            break;
        
        case "url":
            $template["title"] = "Meine Url";
            $template["content_path"] = "url.php";
            break;
        
        case "profile":
            $template["title"] = "Mein Profil";
            $template["content_path"] = "profile.php";
            break;
        
        case "contact":
            $template["title"] = "Kontakt";
            $template["content_path"] = "contact.php";
            break;
        
        case "impressum":
            $template["title"] = "Impressum";
            $template["content_path"] = "impressum.php";
            break;
        
        case "expired":
            $template["title"] = "Abgelaufener Link";
            $template["content_path"] = "expired.php";
            break;
        
        case "wrongLink":
            $template["title"] = "Unbekannter Link";
            $template["content_path"] = "wrongLink.php";
            break;
        
        case "deactivated":
            $template["title"] = "Link deaktiviert";
            $template["content_path"] = "deactivated.php";
            break;
            
        default:
            $template["title"] = "Seite nicht gefunden!";
            $template["content_path"] = "404.php";
            break;
    }

    if (!empty($_SESSION["user"]))                                                  // if session username is set --> user is logged in
    {
    	require __DIR__ . "/templates/indexlogged.php";
    }
    else
    {
    	require __DIR__ . "/templates/index.php";
    }
}

renderIndex();

?>