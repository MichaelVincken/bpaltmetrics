<?php
require_once('/home/thesis-std/database.php'); 
 
//ERROR if this page is loaded without first going trough the previous pages: redirect.
if(!isset($_POST["metric"])) {
    echo '<meta http-equiv="refresh" content="0;URL=visualisation_db_select_parameters.php" />';
    exit;
}
  
if(isset($_GET['metric'])) {
    $metric = $_GET['metric'];
} else {
    $metric = "h-index";
}
if(isset($_GET['include_missing'])) {
    $include_missing = $_GET['include_missing'];
} else {
    $include_missing = FALSE;
}
$array = parallel($con,$metric,$include_missing);
$key = array_keys($array[0])[1];
$array2 = array_msort($array, array($key => SORT_ASC));
echo json_encode(array_Values($array2));
function array_msort($array, $cols)
{
    $colarr = array();
    foreach ($cols as $col => $order) {
        $colarr[$col] = array();
        foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
        $eval .= '$colarr[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);
    $ret = array();
    foreach ($colarr as $col => $arr) {
        foreach ($arr as $k => $v) {
            $k = substr($k,1);
            if (!isset($ret[$k])) $ret[$k] = $array[$k];
            $ret[$k][$col] = $array[$k][$col];
        }
    }
    return $ret;

}


?>
