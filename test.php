<?php
$array = array("a","b","c");
var_dump($array);
unset($array[1]);
var_dump($array);
var_dump(isset($array[0]))    ?>