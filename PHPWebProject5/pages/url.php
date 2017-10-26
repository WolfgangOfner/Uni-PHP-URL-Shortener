<?php
echo "<br/>";

$mode = 0;                                                              // indicates sorting order
require("/../database/dbservice.php");
$dbservice = new dbservice();

if (isset($_GET["sort"]))
{
	switch ($_GET["sort"])
    {
    	case "urlA":                                                    // A == Ascending, D == Descending
            $mode = 1;
            break;
        case "urlD":
            //$mode = 2;
            break;
        case "shortUrlA":
            $mode = 3;
            break;
        case "shortUrlD":
            $mode = 4;
            break;
        case "createDateA":
            $mode = 5;
            break;
        case "createDateD":
            $mode = 6;
            break;
        case "calledA":
            $mode = 7;
            break;
        case "calledD":
            $mode = 8;
            break;
        case "expireTimeA":
            $mode = 9;
            break;
        case "expireTimeD":
            $mode = 10;
            break;
        default:
            
            break;
    }    
}

if (isset($_POST) && !empty($_POST))
{
    $data = $dbservice->FetchEverything();
    $result = array();                                                 // 1 == change, 0 == no change
    $arrayNames = array_keys($_POST);                                  // contains names of $_POST to check which checkbox is activated and which not (if not its not in the list)
    $newExpireTime = array();

    for ($i = 0; $i < count($arrayNames); $i++)
    {
        for ($j = 0; $j < count($arrayNames); $j++)
        {
        	if ($arrayNames["$i"] == "delete" . "$j")
            {
                $dbservice->DeleteURL($j);
            }
        }
    }    
    
    for ($i = 0; $i < count($data); $i++)
    {        
        $change = false;
        
        switch ($data[$i]['activated'] )
        {
            case 0:
                
                for ($j = 0; $j < count($arrayNames); $j++)
                {
                    if ($arrayNames["$j"] == ("checkbox" . "$i"))       // if the link was deactivated and checkbox $i was found, activate link --> indicate with $change[$i] = 1
                    {
                        $change = true;
                        $result["$i"] = 1;
                        break;
                    }
                }
                
                if (!$change)
                {
                    $result["$i"] = 0;
                }
                
                break;
            
        	case 1:
                
                for ($j = 0; $j < count($_POST); $j++)
                {                                                       // if link was activated and checkbox$i is in $_POST dont change it
                    if (isset($_POST["checkbox" . "$i"]) == "on")
                    {
                        $result["$i"] = 0;
                        $change = true;
                        break;
                    }
                }
                
                if (!$change)
                {                                                       // change it
                    $result["$i"] = 1;
                }
                
                break;
        }
    }
    
    $counter = 0;
    
    for ($i = 0; $i < count($_POST) -1; $i++)                                                           // -1 because the last element is always the button  
    {
        if (isset($_POST["expireTimeTB" . "$i"]))                                                        // pretends throwing warnings cause expireTime$i could be undefined   
        {
        	$newExpireTime["$i"] = $_POST["expireTimeTB" . "$i"];
        }	
    }
    
    $dbservice->UpdateActivation($result);    
    $dbservice->UpdateExpireTime($newExpireTime);
}

$data = $dbservice->PrintUrl($mode);

?>