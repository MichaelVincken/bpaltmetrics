<?php
ini_set('display_errors', 1);

require_once('/home/thesis-std/support/bpaltmetrics/database.php');

//ERROR if this page is loaded without first going trough the previous pages: redirect.
if(!isset($_GET["metric"])) {
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

$key = array_keys($array[0]);
$key = $key[1];
$array2 = array_msort($array, array($key => SORT_ASC));
if(isset($_GET['persons'])) {
    $persons = unserialize(urldecode($_GET['persons']));
    foreach($array2 as $key => $element) {
        if(!in_array($element['pId'],$persons)) {
            unset($array2[$key]);
        }
    }
}
recursive_unset($array2,"pId");
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

function recursive_unset(&$array, $unwanted_key) {
    unset($array[$unwanted_key]);
    foreach ($array as &$value) {
        if (is_array($value)) {
            recursive_unset($value, $unwanted_key);
        }
    }
}


?>
