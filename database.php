<?php




require_once('password.php');
//$con = mysqli_connect('p:84.246.4.143','StappaertsDB','Databases1','stappaertsdb',9132) or die('Verbinding naar externe mysqldb gefaald!');
//mysqli_select_db($con,$dbname);
try {
    if(isset($con)) mysqli_close($con);
    $con = mysqli_connect($host,$username,$password,$dbname) or die('connection to database failed.');
} catch (Exception $e) {
    echo  "<script>";
    echo "window.alert('Database connection failed.');";
    echo "</script>";
}



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
//Selects a paper with it's title. If the paper does not exist, it is inserted and the paperId is returned.
function select_paper($title,$con) {
    $query = "SELECT pId FROM paper WHERE title = '{$title}'";
    $result = mysqli_query($con,$query) or die("Cannot execute query. ".mysqli_error($con));
    $returnarray = array();
    while($row = mysqli_fetch_array($result)) {
        array_push($returnarray,$row);
    }
    if(count($returnarray)==0) {
        $query = "INSERT INTO paper(pId,title) VALUES (DEFAULT,'{$title}')";
        mysqli_query($con,$query) or die("Cannot execute query".mysqli_error($con));
        $query = "SELECT pId FROM paper WHERE title = '{$title}'";
        $result = mysqli_query($con,$query) or die("Cannot execute query ".mysqli_error($con));
        $returnarray = array();
        while($row = mysqli_fetch_array($result)) {
            array_push($returnarray,$row);
        }
    }
    return $returnarray;
    
}

//Inserts new authored tuple of paper-person.
function insert_authored($paperId,$personId,$con) {
    $query = "INSERT INTO authored VALUES ('{$paperId}','{$personId}')";
    mysqli_query($con,$query);
    
}

//inserts new paper url in the table for the $network.
function insert_paper_url($network,$paperId,$url,$con) {
    $table_name = $network."_url_paper";
    $query_url = "INSERT INTO {$table_name} VALUES ('{$paperId}','{$url}')";
    mysqli_query($con,$query_url);
    
}




//Retrieves all networks in the "network" table of the database.
function retrieve_networks($con) {
    $query = "SELECT name FROM network";
    $result = mysqli_query($con,$query) or die("Cannot execute query. ".mysqli_error($con));
    $returnarray = array();
    while($row = mysqli_fetch_array($result)) {
        array_push($returnarray,$row[0]);
    }
    
    return $returnarray;
}

//Retrieve all networks this person is in.
function retrieve_networks_person($personId,$con) {
    $query = "SELECT name FROM network";
    $network_array = array();
    $result = mysqli_query($con,$query) or die("Cannot execute query. ".mysqli_error($con));
    while($row = mysqli_fetch_array($result)) {
        array_push($network_array,$row[0]);
    }
    
    $result_array = array();
    foreach($network_array as $network) {
        $table_name = $network."_person";
        $query = "SELECT CASE WHEN EXISTS (
        SELECT *
        FROM {$table_name}
        WHERE pId = '{$personId}'
    )
    THEN 1
    ELSE 0 END";
    $result = mysqli_query($con,$query) or die("Cannot execute query. ".mysqli_error($con));
    $in_network = mysqli_fetch_array($result);
    $in_network = $in_network[0];
    if($in_network) array_push($result_array,$network);                
}
return $result_array;
    
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

/** Retrieve all persons who have authored at least one paper.
*/
function retrieve_persons_with_papers($con) {
$query = "SELECT pId, name, lastName FROM person WHERE EXISTS (SELECT personId FROM Authored WHERE person.pId = personId)";
    $result = mysqli_query($con,$query) or die('Cannot get information. '.mysqli_error($con));
$resultarray = array();
while($row = mysqli_fetch_array($result)) {
    array_push($resultarray,$row);
}
return $resultarray;
    
    
}

/*
* Retrieve all papers.
*/
function retrieve_papers($con) {
$con = mysqli_connect('p:84.246.4.143','StappaertsDB','Databases1','stappaertsdb',9132) or die('Verbinding naar externe mysqldb gefaald!');
$query = "SELECT pId, title FROM paper";
$result = mysqli_query($con,$query) or die('Cannot get information. '.mysqli_error($con));
$resultarray = array();
while($row = mysqli_fetch_array($result)) {
    $row["network"] = "current";
    array_push($resultarray,$row);
}
return $resultarray;
    
}

/*
* Retrieve all urls of this person for this network.
*/
function retrieve_urls($pId,$network,$con) {
$con = mysqli_connect('p:84.246.4.143','StappaertsDB','Databases1','stappaertsdb',9132) or die('Connection failed.');
    
$table = $network."_url";
$query = "SELECT url FROM {$table} WHERE pId = '{$pId}'";
$result = mysqli_query($con,$query) or die('Cannot retrieve url. '.mysqli_error($con));
$resultarray = array();
while($row = mysqli_fetch_array($result)) {
    array_push($resultarray,$row[0]);
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
            $array[$network."_join"] = $network."_person on person.pId = ".$network."_person.pId AND ".$network."_person.Date >= ALL(SELECT Date FROM ".$network."_person WHERE ".$network."_person.pId = person.pID)";
        }     
    }
    $select = "SELECT person.pId, person.name as firstname, person.lastname, ";
    $join = "";
    //$where = "WHERE ";
    $select_array = array();
    //$where_array = array();
    foreach($networks as $network) {
        if(in_array($metric,retrieve_columns($network."_person",$con))) {
            array_push($select_array,$array[$network."_select"]);
            //array_push($where_array,$array[$network."_where"]);
        }
    }
    $select = $select.implode(", ",$select_array);
    //$where = $where.implode(" AND ",$where_array);
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
    $query = $select." FROM person ".$join;
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
//Gets the papers that are in all the included networks and in non of the excluded networks.
function getPapersFromNetworks($included,$excluded,$pId,$con) {
    $query = "SELECT paper.pId,paper.title FROM paper WHERE ";
    $strings = array();
    foreach($included as $include) {
        $table_name = $include."_url_paper";
        array_push($strings,"EXISTS (SELECT pId FROM ".$table_name." WHERE paper.pId = pId)");
    }
    foreach($excluded as $exclude) {
        $table_name = $exclude."_url_paper";
        array_push($strings,"NOT EXISTS (SELECT pId FROM ".$table_name." WHERE paper.pId = pId)");
    }
    $str_and = implode(" AND ", $strings);
    $str_person = " AND EXISTS (SELECT paperId FROM Authored WHERE paperId = paper.pId AND personId = '{$pId}')";
    $query = $query.$str_and.$str_person;
    
    $result = mysqli_query($con,$query) or trigger_error('Cannot retrieve papers. '.mysqli_error($con));
    $resultarray = array();
    while($row = mysqli_fetch_array($result)) {
        array_push($resultarray,$row);
    }
    return $resultarray;
    
    
    
}
?>