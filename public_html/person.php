<?php
require_once('/home/thesis-std/database.php');

function select_person($firstname,$lastname) {
    $query = "SELECT pId FROM person WHERE firstname={$firstname} AND lastname={$lastname}";
    $result = mysql_query($query) or die("Cannot execute query. ".mysql_error());
    $returnarray = array();
    while($row = mysql_fetch_array($result)) {
        arraypush($returnarry,$row);
    }
    if(count($returnarray)==0) {
        $query = "INSERT INTO person(pId,name,lastname) VALUES (DEFAULT,'{$firstname}','{$lastname}')";
        mysql_query($query) or die("Cannot execute query".mysql_error());
        $query = "SELECT pId FROM person WHERE firstname={$firstname} AND lastname={$lastname}";
        $result = mysql_query($query) or die("Cannot execute query".mysql_error());
        $returnarray = array();
        while($row = mysql_fetch_array($result)) {
            arraypush($returnarry,$row);
        }
    }
    return $returnarray;
    
}
var_dump(select_person("Erik","Duval"));

?>