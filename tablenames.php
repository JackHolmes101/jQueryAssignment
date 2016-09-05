<?php
include "connect.php"; 

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
        $sql = $dbh->prepare('SHOW TABLES');
        $sql->execute();

    if ( $sql->columnCount() > 0 ){
        $json = '{ "names" : [ ';
        $i = 0;
        while ($row = $sql->fetch() ){
            $i++;
            if ( $i > 1 )
                $json .=  ', ';
            $json .= '"' . $row[0] . '"';
        }
        $json .= ' ] } ';
        echo($json);
    }
    else
        echo '{ "names" : [ ] }';
    }
    catch (PDOException $e) {
        $ok = false;
        echo("Database access unsuccessful");
    }
}
$dbh = null;
?>