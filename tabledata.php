<?php
include "connect.php"; 

$ok=true;
try {
	$dbh = new PDO('mysql:host='.$hostname.'; dbname='.$database, $username, $password);
}
catch (PDOException $e) {
	$ok = false;
	echo("Database access unsuccessful");
}
if($ok)
{
    $tablename = $_REQUEST["tablename"];
    try {
        //Create array of prepared sql commands to select * from db tables avoiding SQL Injection
        $sql = $dbh->prepare("show tables");

        $sql->execute();

        if ( $sql->columnCount() > 0 ){
            while ($row = $sql->fetch() )
                $sqls[ $row[0] ] = "select * from " . $row[0] . ";";
        }

        $sql = $dbh->prepare($sqls[$tablename]);
        $sql->execute();

        if ( $sql->columnCount() > 0 ){
            $records = $sql->fetchAll(PDO::FETCH_ASSOC);
            $json = '{ "columns" : ' . $sql->columnCount() . ', "rows" : ' . count($records) . ', ';

            $column = 0;
            $json .= '"headings" : [ ';
            foreach ($records[0] as $key => $value){
                $column++;
                if ( $column > 1 )
                    $json .=  ', ';
                $json .= '"' . $key . '"';
            }
            $json .= ' ], ';
            $json .= '"data" : [ ';

            $row = 0;
            foreach ($records as $record){
                $row++;
                if ( $row > 1 )
                    $json .=  ', ';
                $json .= '[ ';
                $column = 0;
                foreach ($record as $data)
                {
                    $column++;
                    if ( $column > 1 )
                        $json .=  ', ';
                    $json .= '"' . $data . '"';
                }
                $json .= ' ]';
            }
            $json .= '] }';
            echo($json);
        }
        else
            echo '{"columns" : 0, "rows" : 0 }';  
    }
    catch (PDOException $e) {
        $ok = false;
        echo("Database access unsuccessful");
    }
}
$dbh = null;
?>