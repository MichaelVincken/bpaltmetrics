<?php
$page_title = "Insertion Completed";
include('/home/thesis-std/support/bpaltmetrics/menu.php');    
include('/home/thesis-std/support/bpaltmetrics/login.php');
//Help mouseover:
$mouseOverString = "Person inserted into the database. You can go to any page you like.";
include('tooltip.php');

?>
<a href = "insert_person1.php"> Insert a new person </a>
<meta http-equiv="refresh" content="0;URL=visualisation_db_select_parameters.php" />


<?php include('/home/thesis-std/support/bpaltmetrics/footer.php')?>
