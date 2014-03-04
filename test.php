<?php
require('database.php');

$pId = 1;
//retrieve networks relevant for this user.
$networks = retrieve_networks_person($pId,$con);
//Now calculate each set.

//First calculate which networks each set has to have.
$set_array = array();
//Loop through each possible combination   
for ($i = 0; $i < pow(2, count($networks)); $i++) {   
    //For each combination check if each bit is set  
    $array = array();
    for ($j = 0; $j < count($networks); $j++) {  
       //Is bit $j set in $i?  
        if (pow(2, $j) & $i) {
            array_push($array,$networks[$j]);  
            }     
    }  
   array_push($set_array,$array);  
}
sort($set_array);
//Calculate the include networks and the not-include networks.
$set_array = array_slice($set_array,1);
$new_set_array = array();
foreach($set_array as $set) {
    $array = array();
    $array[0]=$set;
    $array[1]=array_diff($networks,$set);
    array_push($new_set_array,$array);
}
//Get actual records.
$set_records = array();
foreach($new_set_array as $set) {
    $set_name = implode(' ∩ ',$set[0]);
    $set_records[$set_name] = getPapersFromNetworks($set[0],$set[1],$pId,$con);
}
//Venn diagram?
$venn_nr_sets = count($networks);
$sets = array();
var_dump($set_array);
for($i = 0;$i<$venn_nr_sets;$i++) {
    $set_name = array_keys($set_records)[$i];
    array_push($sets,'{label: "'.$set_name.'" ,size: '.count($set_records[$set_name]).'}');
}
$sets = implode(', ',$sets);
$sets = '['.$sets.']';

$overlaps = array();

//Calculate sets with numbers.
$elements = range(0,count($networks)-1);
$array_numbers = array();
//Loop through each possible combination   
for ($i = 0; $i < pow(2, count($elements)); $i++) {   
    //For each combination check if each bit is set  
    $array = array();
    for ($j = 0; $j < count($elements); $j++) {  
       //Is bit $j set in $i?  
        if (pow(2, $j) & $i) {
            array_push($array,$elements[$j]);  
            }     
    }  
   array_push($array_numbers,$array);  
}
sort($array_numbers);
var_dump($array_numbers);

?>