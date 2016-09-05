<?php
//header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors',1);

include "connect.php"; 

// this is just a test
//send back to the ajax request the request

$ok=true;
try {
	$dbh = new PDO('mysql:host='.$hostname.';dbname='.$database, $username, $password);
}
catch (PDOException $e) {
	$ok = false;
	echo("Database access unsuccessful");
}
if($ok)
{
	try {			
		$table;
		$params = array();
		$bindArr = array();
		
  		foreach($_POST as $key => $value) {
  			if($key != 'tablename'){
  				echo "POST parameter '$key' has '$value'. ";
  				//array_push($fields, $key);  				
  				array_push($params, $value);
  				array_push($bindArr, '?');
  			}
  			else{
  				echo "POST parameter is a table name '$key' has '$value'. ";
  				$table = $value;
  			}
		}

		$query = 'INSERT INTO ';
		$query .= $table;
		$query .= " VALUES (".implode(", ", $bindArr)."); ";
		echo $query;
		
		$sql = $dbh->prepare($query);
    	$sql->execute($params);
  	}
  	catch (PDOException $e) 
  	{
		$ok = false;
	  	echo("Database access unsuccessful");
  	}
}
$dbh = null;

?>
