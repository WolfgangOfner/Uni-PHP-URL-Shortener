<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?= $template["title"] ?></title>
 <meta charset="iso-8859-1" />
    <link rel="stylesheet" type="text/css" href="../CSS/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="../CSS/jquery-ui-1.10.4.custom.css" />
    <link rel="stylesheet" type="text/css" href="../CSS/MainCSS.css" />
    <link type="text/css" href="Scripts/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../Javasript/bootstrap.js"></script>
    <script type="text/javascript" src="../Javascript/jquery-1.6.4.js"></script>
    <script type="text/javascript" src="../Javascript/jquery-ui-1.10.4.custom.js"></script>
</head>
<body class="myBody">

    <header class="col-md-12">
        <h1>Wolfgangs wunderbarer Linkshortener</h1>
    </header>

    <nav>
        <table>
            <tr>
                <td class="line"><a href="../index.php?page=home">Home &nbsp;</a><a href="../index.php?page=login"> Login &nbsp;</a><a href="../index.php?page=register">Registrierung &nbsp;</a></td>
            </tr>
        </table>
    </nav>

    <aside class="col-md-2 hidde-xs">
        <div>
            <h3>Werbung: </h3>
            <p>

                <a href="http://www.lackiererei-m-ofner.at">
                    <img src="/../images/ofner.png" width="190" height="100" title="Lackiererei Ofner Logo" alt="Lackiererei Ofner Logo" /></a>
            </p>
            <p>
                <a href="http://www.fhwn.ac.at">
                    <img src="/../images/fhwn.jpg" width="190" height="100" title="FH Wiener Neustadt Logo" alt="FH Wiener Neustadt Logo" /></a>
            </p>
            <p>Finde uns auf:</p>
            <p>
                <a href="https://twitter.com">
                    <img src="/../images/twitter.png" width="50" height="50" title="Twitter Logo" alt="Twitter Logo" /></a>
                <a href="http://www.facebook.com">
                    <img src="/../images/facebook.gif" width="50" height="50" title="Facebook Logo" alt="Facebook Logo" /></a>
                <a href="http://www.youtube.com/">
                    <img src="/../images/youtube.png" width="50" height="50" title=">outube Logo" alt="Youtube Logo" /></a>
            </p>
        </div>
    </aside>

    <section class="col-md-8">
        <?php
        
        require __DIR__ . "/../pages/" . $template["content_path"];
        ?>
    </section>

    <footer class="divFoot">
        <div>
            <a href="../index.php?page=contact">Kontakt &nbsp;</a>
            <a href="../index.php?page=impressum">Impressum &nbsp;</a>
        </div>
    </footer>

</body>
</html>
