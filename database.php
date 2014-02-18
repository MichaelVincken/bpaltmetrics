<?php
//Database specifics
$host = "84.246.4.143:9132";
$username = "StappaertsDB";
$password = "Database1";
$dbname = "stappaertsdb";

$con = mysqli_connect($host,$username,$password);
mysqli_select_db($con,$dbname);

//Selects a person, with his first and lastname. If the person does not exist, s/he is inserted in the database and returned.
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

//Retrieves all networks in the "network" table of the database.
function retrieve_networks($con) {
    $query = "SELECT name FROM network";
    $network_array = array();
    $result = mysqli_query($con,$query) or die("Cannot execute query. ".mysqli_error($con));
    $returnarray = array();
    while($row = mysqli_fetch_array($result)) {
        array_push($returnarray,$row[0]);
    }
    
    return $returnarray;
}

//Retrieves all column names of this table.
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

//Looks if a first and lastname are already in the database.
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
    $query = "SELECT * FROM {$table_name} WHERE pId = '{$pId}' AND url = '{$url}'";
    $result = mysqli_query($con,$query) or die('Cannot insert url. '.mysqli_error($con));
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

//checks all tables of all networks for all metrics that can be compared:: have two or more occurences.
function get_all_comparable_metrics($con) {
    $network_names = array();
    $network_names = retrieve_networks($con);
    $metrics_array = array();
    foreach ($network_names as $network) {
        $retrieved_columns = retrieve_columns($network."_person",$con);
        for($i=3;$i < count($retrieved_columns); $i++) {
            array_push($metrics_array, $retrieved_columns[$i]);
        }     
    }
    $count_array = array_count_values($metrics_array);
    
    arsort($count_array);
    var_dump($count_array);
    $return_array = array();
    foreach($count_array as $metric => $value) {
        if($value >= 2) {
            if($metric != "study_field")array_push($return_array,$metric);
        } else {
            break;
        }
    }
        
    return $return_array;
        
}

function retrieve_persons($con) {
    $query = "SELECT pId, name, lastName FROM person";
    $result = mysqli_query($con,$query) or die('Cannot get information. '.mysqli_error($con));
    $resultarray = array();
    while($row = mysqli_fetch_array($result)) {
        array_push($resultarray,$row);
    }
    return $resultarray;
}

/*
*   $con
*   $metric ex. citations
*   $left_join: perform left join? => keep person with missing value or not
*/
function parallel($con,$metric,$left_join) {
    $array = array();
    $networks = retrieve_networks($con);
    //MAke strings to be connected for the query. 3 parts: SELECT, JOIN (keep people with missing values) and WHERE
    foreach($networks as $network) {
        if(in_array($metric,retrieve_columns($network."_person",$con))) {
            $array[$network."_select"] = $network."_person". ".pId, ". $network."_person.`".$metric."` as `".$network."`";
            $array[$network."_join"] = $network."_person on person.pId = ".$network."_person.pId";
            $array[$network."_where"] = $network."_person.Date >= ALL(SELECT Date FROM ".$network."_person WHERE ".$network."_person.pId = person.pID)";
        }     
    }
    $select = "SELECT person.pId, person.name as firstname, person.lastname, ";
    $join = "";
    $where = "WHERE ";
    $select_array = array();
    $where_array = array();
    foreach($networks as $network) {
        if(in_array($metric,retrieve_columns($network."_person",$con))) {
            array_push($select_array,$array[$network."_select"]);
            array_push($where_array,$array[$network."_where"]);
        }
    }
    $select = $select.implode(", ",$select_array);
    $where = $where.implode(" AND ",$where_array);
    if($left_join) {
        foreach($networks as $network) {
            if(in_array($metric,retrieve_columns($network."_person",$con))) {
                $join = $join."LEFT JOIN ".$array[$network."_join"]." ";
            }
        }
    } else {
        foreach($networks as $network) {
            if(in_array($metric,retrieve_columns($network."_person",$con))) {
                $join = $join."JOIN ".$array[$network."_join"]." ";
            }
        }
    }
    //make everything into one giant query.
    $query = $select." FROM person ".$join." ".$where;
    $result = mysqli_query($con,$query) or die('Cannot get information. '.mysqli_error($con));
    $resultarray = array();
    while($row = mysqli_fetch_array($result)) {
        array_push($resultarray,$row);
    }
    //Only return data that is needed.
    $return_array = array();
    foreach($resultarray as $result) {
        $array = array();
        $array["name"] = $result["firstname"]. " ".$result["lastname"] ;
        foreach($networks as $network) {
            if(in_array($metric,retrieve_columns($network."_person",$con))) {
            $array[$network] = $result[$network];
        }
        }
        array_push($return_array,$array);
    }
    
    return $return_array;
    
    
        
}
?>