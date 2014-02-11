<?php
$host = "localhost";
$username = "bpaltmetrics";
$password = "ErikDuv@lR0ckz";
$dbname = "bpaltmetrics";

$con = mysqli_connect('localhost',"bpaltmetrics","ErikDuv@lR0ckz");
mysqli_select_db($con,"bpaltmetrics");

function select_person($firstname,$lastname,$con) {
    $query = "SELECT pId FROM person WHERE name='{$firstname}' AND lastname='{$lastname}'";
    $result = mysqli_query($con,$query) or die("Cannot execute query. ".mysqli_error($con));
    $returnarray = array();
    while($row = mysqli_fetch_array($result)) {
        array_push($returnarray,$row);
    }
    if(count($returnarray)==0) {
        $query = "INSERT INTO person(pId,name,lastname) VALUES (DEFAULT,'{$firstname}','{$lastname}')";
        mysqli_query($con,$query) or die("Cannot execute query".mysqli_error($con));
        $query = "SELECT pId FROM person WHERE name='{$firstname}' AND lastname='{$lastname}'";
        $result = mysqli_query($con,$query) or die("Cannot execute query ".mysqli_error($con));
        $returnarray = array();
        while($row = mysqli_fetch_array($result)) {
            array_push($returnarray,$row);
        }
    }
    return $returnarray;
    
}

function retrieve_networks($con) {
    $query = "SELECT name FROM network";
    $network_array = array();
    $result = mysqli_query($con,$query) or die("Cannot execute query. ".mysqli_error($con));
    $returnarray = array();
    while($row = mysqli_fetch_array($result)) {
        array_push($returnarray,$row);
    }
    
    return $returnarray;
}

function retrieve_columns($table, $con) {
    $query = "describe " . $table;
    $result = mysqli_query($con,$query) or die("Cannot retrieve columns. ".mysqli_error($con));
    $resultarray = array();    
    while($row = mysqli_fetch_array($result)) {
        array_push($resultarray,$row);
    }
    
    $returnarray = array();
    foreach ($resultarray as $col) {
        array_push($returnarray,$col[0]);
    }
    
    return $returnarray;
}

function already_in_database($firstname,$lastname,$con) {
    $query = "SELECT * FROM person WHERE name = '{$firstname}' AND lastname = '{$lastname}'";
    $result = mysqli_query($con,$query) or die("Cannot execute query".mysqli_error($con));
    $resultarray = array();    
    while($row = mysqli_fetch_array($result)) {
        array_push($resultarray,$row);
    }
    if(count($resultarray) > 0) {
        return 1;
    } else {
        return 0;
    }   
}

//Insert URL insert URL in $network_name or does nothing when the url has already been linked with $pId
function insert_url($pId,$network_name,$url,$con) {
    $table_name = $network_name."_url";
    $query = "SELECT * FROM '{$table_name}' WHERE pId = '{$pId}' AND url = '{$url}'";
    $result = mysqli_query($con,$query) or die('Cannot insert url. ').mysqli_error($con);
    $resultarray = array();
    while($row = mysqli_fetch_array($result)) {
        array_push($resultarray,$row);
    }
    if(count($resultarray) > 0) {
    } else {
        $query = "INSERT INTO {$table_name}(pId,url) VALUES ('{$pId}','{$url}') ";
        mysqli_query($con,$query) or die("Cannot insert url. ".mysqli_error($con));
    }   
}

?>