<?php
$page_title = "Checkbox";
require_once('menu.php');
require_once('database.php');

$checkboxes = isset($_POST['persons']) ? $_POST['persons'] : array();
$resultPersons = array();
foreach($checkboxes as $person) {
    array_push($resultPersons, unserialize(urldecode($person)));
}

var_dump($resultPersons);

include('footer.php')?>