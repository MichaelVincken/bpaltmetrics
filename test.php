<?php
include('database.php');

$networks = retrieve_networks_person(1,$con);
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
    $string = implode(' ∩ ',$set[0]);
    var_dump(html_entity_decode($string));
}
?>