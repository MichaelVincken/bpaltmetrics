<?php
$page_title = "visualise papers";
require('menu.php');
require('database.php');

$pId = mysql_real_escape_string($_POST["pId"]);
//retrieve networks relevant for this user.
$networks = retrieve_networks_person($pId,$con);
//Now calculate each set.

require('footer.php');
?>
