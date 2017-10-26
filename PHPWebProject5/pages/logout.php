<?php

session_destroy();

echo "<script type=\"text/javascript\">\n";                                 // redirect to home after logout
echo "<!--\n";
echo "location.href = '";
echo "/../index.php?page=home";
echo "';\n";
echo "//-->\n";
echo "</script>\n";

?>