<?php

class dbservice
{
	public function DbLogin($data)
	{
		$pdo = $this->CreateDatabaseConnection();
        
        $readRow = $pdo->query("SELECT * FROM tbUser WHERE username = '$data[username]'");                      // check if username is usernam
        $row = $readRow->fetch(PDO::FETCH_ASSOC);
        
        if (!$row)
        {                                                                                                           // if not username check if username is email
        	$readRow = $pdo->query("SELECT * FROM tbUser WHERE email = '$data[username]'");    
            $row = $readRow->fetch(PDO::FETCH_ASSOC);
        }
              
        if ($row)
        {         
            if ((( $data["username"] == $row["username"]) || ( $data["username"] == $row["email"])) && (crypt( $data["password"], $row["salt"]) == $row["password"] ))      // if username or email and password match
            {  
                $_SESSION["user"] = $row["username"];                
                $_SESSION["id"] = $row["Id"];
                $_SESSION["email"] = $row["email"];
                $_SESSION["firstName"] = $row["firstName"];
                $_SESSION["lastName"] = $row["lastName"];
                $_SESSION["adress"] = $row["adress"];
                $_SESSION["birthday"] = $row["birthday"];
                session_regenerate_id(true); 
                
                echo "<script type=\"text/javascript\">\n";                     // automatic forwarding to home, header isnt working with template (and automatic forwarding)
                echo "<!--\n";
                echo "location.href = '";
                echo "/../index.php?page=welcome";
                echo "';\n";
                echo "//-->\n";
                echo "</script>\n";
            }
            else
            {
                echo "<label class=labelRed>Passwort falsch</label><br />";
            }
        }
        else
        {
            echo "<label class=labelRed>Benutzer existiert nicht</label><br />"; 
        }
	}

	public function AddDBEntry($data)
	{    
        $pdo = $this->CreateDatabaseConnection();
        $existingUser = $this->CheckUser($data);                                                                    // check if user is already existing
        $existingEmail = $this->checkEmail($data);                                                                  // check if email is already existing
        
        if (!$existingUser && !$existingEmail)
        {                
            $data = $this->CryptPW($data);
            
            if (empty($message["birthday"]))                                                                        // without this birthday would be 1.1.1900 if empty
            {
            	$sql = "INSERT INTO tbUser (email, username, password, salt, firstName, lastName, adress) VALUES ('$data[email]', '$data[username]', '$data[password]', '$data[salt]', '$data[firstName]', '$data[lastName]', '$data[adress]')";
            }
            else
            {
                $sql = "INSERT INTO tbUser (email, username, password, salt, firstName, lastName, adress, birthday) VALUES ('$data[email]', '$data[username]', '$data[password]', '$data[salt]', '$data[firstName]', '$data[lastName]', '$data[adress]', '$data[birthday]')";
            }
            
            $pdo->query($sql);	
            
            echo "<label class=labelGreen>Sie wurden registriert</label><br />"; 
        }
        
        if ($existingEmail)
        {
            echo "<label class=labelRed>Email Adresse bereits vorhanden</label><br />"; 
        }
        
        if ($existingUser)
        {
        	echo "<label class=labelRed>Benutzername bereits vorhanden</label><br />"; 
        }
	}

    public function UpdateProfil($message)
    {
        $pdo = $this->CreateDatabaseConnection();
        
        $readRow = $pdo->query("SELECT * FROM tbUser WHERE username = '$message[usernameOld]'");
        $row = $readRow->fetch(PDO::FETCH_ASSOC);
        
        $existngEmail = $this->checkEmail($message);
        $existingUser = $this->checkUser($message);
        
        switch ($message)
        {
        	case (!empty($message["email"])):
                if (!$existingEmail)
                {
                    $sql = "Update tbUser SET email = '$message[email]' WHERE Id= '$row[Id]'";
                    $pdo->query($sql);
                    $_SESSION["email"] = $message["email"];                                                         // no break to go through all cases
                }   
                else
                {
                    echo "<label class=labelRed>Email Adresse bereits vorhanden</label><br />";
                    break;
                }                                                     
            
            case (!empty($message["username"])):
                {
                    if (!$userExisting)
                    {
                            $sql = "Update tbUser SET username = '$message[username]' WHERE Id= '$row[Id]'";
                            $pdo->query($sql);
                            $_SESSION["user"] = $message["user"]; 
                        }
                    else
                    {
                        echo "<label class=labelRed>Benutzername bereits vorhanden</label><br />"; 
                        break;
                    } 
                }
            
            case (!empty($message["password"])):
                {
                    $message = $this->CryptPW($message);
                    $sql = "Update tbUser SET password = '$message[password]' WHERE Id= '$row[Id]'";
                    $pdo->query($sql);
                }
            
            case (!empty($message["firstName"])):
                {
                    $sql = "Update tbUser SET firstName = '$message[firstName]' WHERE Id= '$row[Id]'";
                    $pdo->query($sql);
                    $_SESSION["firstName"] = $message["firstName"]; 
                }
            
            case (!empty($message["lastName"])):
                {
                    $sql = "Update tbUser SET lastName = '$message[lastName]' WHERE Id= '$row[Id]'";
                    $pdo->query($sql);
                    $_SESSION["lastName"] = $message["lastName"]; 
                }
            
            case (!empty($message["adress"])):
                {
                    $sql = "Update tbUser SET adress = '$message[adress]' WHERE Id= '$row[Id]'";
                    $pdo->query($sql);
                    $_SESSION["adress"] = $message["adress"]; 
                }
            
            case (!empty($message["birthday"])):
                {
                    $sql = "Update tbUser SET birthday = '$message[birthday]' WHERE Id= '$row[Id]'";
                    $pdo->query($sql);
                    $_SESSION["birthday"] = $message["birthday"]; 
                }
            
            default:
                echo "<label class=labelGreen>Benutzerprofil aktualisiert</label><br />";
        }
    }
    
    public function DeleteProfil($message)
    {
        $pdo = $this->CreateDatabaseConnection();
        
        $pdo->query("DELETE FROM tbShortUrl WHERE userId = '$_SESSION[id]'");
        $pdo->query("DELETE FROM tbUser WHERE username = '$message[usernameOld]'");
        
        session_destroy();
        
        echo "<script type=\"text/javascript\">\n";                                             // redirect to home after user was deleted
        echo "<!--\n";
        echo "location.href = '";
        echo "/../index.php?page=home";
        echo "';\n";
        echo "//-->\n";
        echo "</script>\n";
    }
    
    public function DeleteURL($rowNumber)
    {
        $pdo = $this->CreateDatabaseConnection();
        
        $result = $pdo->query("SELECT * FROM tbShortUrl WHERE userId = '$_SESSION[id]'");           // get all Url from the user
        $row = $result->fetchAll();
        $id = $row[$rowNumber]["Id"];                                                               // get id of url which should be deleted
        
        $pdo->query("DELETE FROM tbShortUrl WHERE Id = '$id'");
    }
    
    public function ShortenUrl($url)
    {
        $pdo = $this->CreateDatabaseConnection();
        
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $shortKey = "";
        $test = strlen($characters);
        for ($i = 0; $i < 8; $i++)                                                                 // create random array with adding every loop one random element
        {
            $shortKey .= $characters[rand(0, strlen($characters) -1)];                             // strlen = length of characters, -1 for the last index                        
        }
        
        $shortUrl = "$shortKey";
        
        $readShortUrl = $this->checkShortUrl($shortUrl);
        
        if ($readShortUrl)
        {                                                                                           // if shortKey is already used call method again to create a new one
        	$this->ShortenUrl($url);                          
        }      
        
        $createDate = date("Y/m/d G:i");                                                            // Year/month/day 24h:minute, seconds not needed
        
        if (isset($_SESSION["id"]))                                                                 // if the user is logged in
        {
        	$sql = "INSERT INTO tbShortUrl (userId, url, shortUrl, createDate) VALUES ('$_SESSION[id]', '$url', '$shortUrl', '$createDate')";
            $pdo->query($sql);	
        }
        else
        {
            $expireDay = date("Y/m/d G:i", time() + 86400);                                         // now plus 1 day   
            
            $sql = "INSERT INTO tbShortUrl (url, shortUrl, expireTime, createDate) VALUES ('$url', '$shortUrl', '$expireDay', '$createDate')";
            $pdo->query($sql);	
        }
        
        echo "<br/>Ihre verkürzte URL lautet: <a href='http://localhost:3475/$shortUrl'> http://localhost:3475/$shortUrl </a>";
    }
    
    public function Redirect($key)
    {
        $pdo = $this->CreateDatabaseConnection();
        
        $readUrl = $pdo->query("SELECT * FROM tbShortUrl WHERE shortUrl = '$key'");
        $row = $readUrl->fetch(PDO::FETCH_ASSOC);
        
        if ($row)
        {
            if ($row["activated"] == 1)
            {
            	$checkDate = date("Y/m/d G:i");
                $dateTimestamp1 = strtotime($checkDate);
                $dateTimestamp2 = strtotime($row["expireTime"]);
                
                if ($dateTimestamp1 < $dateTimestamp2 || $row["expireTime"] == null)                                // if expire date is later than now or not set                
                {
                    $row["called"] += 1;
                    $sql = "Update tbShortUrl SET called = '$row[called]' WHERE Id= '$row[Id]'";
                    $pdo->query($sql);
                    header("Location: http://$row[url]");
                }
                else
                {
                    echo "<script type=\"text/javascript\">\n";                                                     // link expired
                    echo "<!--\n";
                    echo "location.href = '";
                    echo "/../index.php?page=expired";
                    echo "';\n";
                    echo "//-->\n";
                    echo "</script>\n";
                }
            }
            else
            {
                echo "<script type=\"text/javascript\">\n";                                                         // link deactivated
                echo "<!--\n";
                echo "location.href = '";
                echo "/../index.php?page=deactivated";
                echo "';\n";
                echo "//-->\n";
                echo "</script>\n";
            }        	          
        }
        else
        {
            echo "<script type=\"text/javascript\">\n";                                                             // wrong link
            echo "<!--\n";
            echo "location.href = '";
            echo "/../index.php?page=wrongLink";
            echo "';\n";
            echo "//-->\n";
            echo "</script>\n";
        }
    }
    
    public function FetchEverything()
    {
        $pdo = $this->CreateDatabaseConnection();
        $result = $pdo->query("SELECT * FROM tbShortUrl WHERE userId = '$_SESSION[id]'");   
        $data = $result->fetchAll();
        
        return $data;
    }
    
    public function PrintUrl($mode)
    {
        $pdo = $this->CreateDatabaseConnection();
        
        $result = $pdo->query("SELECT * FROM tbShortUrl WHERE userId = '$_SESSION[id]'");
        $found = false;
        $checked = array();
        $delete = array();
        $expireId = array();
        $i = 0;    
        
        $dataFetch = $result;
        $data = $dataFetch->fetchAll();
        $sql ="SELECT * FROM tbShortUrl WHERE userId = '$_SESSION[id]'";
        
        switch ($mode)
        {                                                                                                       // define how to sort
            case 1:
                $sql .= " ORDER BY url";
                break;
            case 2:
                $sql .= " ORDER BY url DESC";
                break;
            case 3:
                $sql .= " ORDER BY shortUrl";
                break;
            case 4:
                $sql .= " ORDER BY shortUrl DESC";
                break;
            case 5:
                $sql .= " ORDER BY createDate";
                break;
            case 6:
                $sql .= " ORDER BY createDate DESC";
                break;
            case 7:
                $sql .= " ORDER BY called";
                break;
            case 8:
                $sql .= " ORDER BY called DESC";
                break;
            case 9:
                $sql .= " ORDER BY expireTime";
                break;
            case 10:
                $sql .= " ORDER BY expireTime DESC";
                break;
            default:
                break;
        }
        
        $result = $pdo->query($sql);
        
        if (count($data) != 0)                                                                                      // if no links are stored
        {      	
            echo "<form method='post'accept-charset='UTF-8'>";                                                                           // table for url
            echo "<table class='printUrl' border='1'>";
            echo "<tr><td><b>Original URL &nbsp;<a href='../index.php?page=url&sort=urlA'>&#9650;<a href='../index.php?page=url&sort=urlD'>&#9660;</a></b></td>
            <td><b>Gekürzte URL &nbsp;<a href='../index.php?page=url&sort=shortUrlA'>&#9650;<a href='../index.php?page=url&sort=shortUrlD'>&#9660;</a></b></td>
            <td><b>Erstelldatum &nbsp;<a href='../index.php?page=url&sort=createDateA'>&#9650;<a href='../index.php?page=url&sort=createDateD'>&#9660;</a></b></td>
            <td><b>Ablaufdatum &nbsp;<a href='../index.php?page=url&sort=expireTimeA'>&#9650;<a href='../index.php?page=url&sort=expireTimeD'>&#9660;</a></b></td>
            <td><b>Aufgerufen &nbsp;<a href='../index.php?page=url&sort=calledA'>&#9650;<a href='../index.php?page=url&sort=calledD'>&#9660;</a></b></td>
              <td><b>Aktiviert &nbsp;</b></td>
                <td><b>Löschen &nbsp;</b></td>";
            
            while ($row = $result->fetch(PDO::FETCH_ASSOC))
            {
                $checked["$i"] = "checkbox" . "$i";
                $expireId["$i"] = "expireTimeTB" . "$i";
                $formatedCreateDate = substr("$row[createDate]" ,0,16);                                              // substr deletes 00:00:00 which appears frome nowhere
                $formatedexpireTime = substr("$row[expireTime]" ,0,16);   
                $delete["$i"] = "delete" . "$i";
                
                
                echo "<tr><td><a href='http://$row[url]'> $row[url] </a> </td>";
                echo "<td><a href='$row[shortUrl]'> http://localhost:3475/$row[shortUrl] </a></td>";
                echo "<td>$formatedCreateDate Uhr</td>";
                
                if ($formatedexpireTime)
                {
                    echo "<td> <input tpye='text' class='expireTimeTB' id='$expireId[$i]' name='$expireId[$i]' value='$formatedexpireTime Uhr' readonly /></td>";
                }
                else                                                                                                                        // if no time is set dont display "Uhr"
                {
                    echo "<td> <input tpye='text' class='expireTimeTB' id='$expireId[$i]' name='$expireId[$i]' value='$formatedexpireTime' readonly /></td>";
                }
                
                echo "<td>$row[called]</td>";
                
                if ($row["activated"] == 1)
                {                                                                                                                           // if link is activated tick checkbox
                    echo "<td><input type='checkbox' name='$checked[$i]' id='$checked[$i]' checked='checked'/></td>";
                }
                else
                {
                    echo "<td><input type='checkbox' name='$checked[$i]' id='$checked[$i]' /></td>";
                }
                
                echo "<td><input type='submit' id='$delete[$i]' name='$delete[$i]' value='Löschen'/></td></tr>";
                
                $i++;
                $found = true;
            }	
            
            echo "</table>";
            echo "<input type='submit' id='btnUrlChange' name='btnUrlChange' value='Aktualisiere Links' /><br/>";
            echo "</form><br/>";
        }
        else
        {
            echo "<label class=labelRed>Zur Zeit sind keine gekürzten URLs vorhanden</label><br />";
        }
        
        return $data;
    }
    
    public function UpdateActivation($changes)
    {
        $pdo = $this->CreateDatabaseConnection();     
        $result = $pdo->query("SELECT * FROM tbShortUrl WHERE userId = '$_SESSION[id]'");
        $i = 0;
        
        while ($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            if ($row["activated"] == 0)
            {                           
                if ($changes[$i] == 1)                                                              // change
                {
                    $sql = "Update tbShortUrl SET activated = '1' WHERE Id= '$row[Id]'";
                    $pdo->query($sql);
                }
            }
            else
            {
                if ($changes[$i] == 1)                                                              // change
                {
                    $sql = "Update tbShortUrl SET activated = '0' WHERE Id= '$row[Id]'";
                    $pdo->query($sql);
                }
            }
            
            $i++;
        }
    }
    
    public function UpdateExpireTime($changes)
    {   
        $pdo = $this->CreateDatabaseConnection();     
        $result = $pdo->query("SELECT * FROM tbShortUrl WHERE userId = '$_SESSION[id]'");
        $i = 0;
        
        while ($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            if ($row["expireTime"] != $changes[$i])                                                 // if expireTime got changed
            {                    
                $formatedexpireTime = substr("$changes[$i]" ,0,16);   
                $sql = "Update tbShortUrl SET expireTime = '$formatedexpireTime' WHERE Id= '$row[Id]'";
                $pdo->query($sql);
            }
            
            $i++;
        }
    }
    
    private function checkUser($message)
    {
        $pdo = $this->CreateDatabaseConnection();
        
        $readUser = $pdo->query("SELECT * FROM tbUser WHERE username = '$message[username]'");         
        $readUser =  $readUser->fetch(PDO::FETCH_ASSOC);   
        
        return $readUser;                                                     
    }
    
    private function checkEmail($message)
    {
        $pdo = $this->CreateDatabaseConnection();          
        $readEmail= $pdo->query("SELECT * FROM tbUser WHERE email = '$message[email]'");  
        
        $readEmail = $readEmail->fetch(PDO::FETCH_ASSOC);
        
        return $readEmail;                                                     
    }
    
    private function checkShortUrl($shortUrl)
    {
        $pdo = $this->CreateDatabaseConnection();          
        $readShortUrl= $pdo->query("SELECT * FROM tbShortUrl WHERE shortUrl = '$shortUrl'");  
        
        $readShortUrl = $readShortUrl->fetch(PDO::FETCH_ASSOC);
        
        return $readShortUrl;                      
    }
    
    private function CryptPW($message, $rounds = 3)
    {
        $message["salt"] = "";
        $saltChars = array_merge(range('A','Z'), range('a', 'z'), range('0', '9'));                     // create array with values between A-Z, a-z and 0-9
        
        for ($i = 0; $i < 20; $i++)
        {
        	$message["salt"] .= $saltChars[array_rand($saltChars)];                                     // randomize array
        }
        
        $message["password"] = crypt($message["password"], $message["salt"]);       // printf formats the string, $2y$ indicates that the blowfhish encryption is used
        
        return $message;
    }
    
    private function CreateDatabaseConnection()
    {
        $connectionString = 'sqlsrv:Server=(localdb)\v11.0;AttachDBFileName=' . __DIR__ . '\\DB.mdf;';

        $pdo= new PDO($connectionString);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $pdo;
    }
}
?>