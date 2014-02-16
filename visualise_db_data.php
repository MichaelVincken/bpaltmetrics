<?php
require_once('database.php');    
$metric = $_GET['metric'];
$include_missing = $_GET['include_missing'];

echo json_encode(parallel($con,$metric,$include_missing));


?>
