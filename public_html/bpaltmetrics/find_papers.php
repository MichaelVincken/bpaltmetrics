<?php
$page_title = "find papers";
include('/home/thesis-std/support/bpaltmetrics/menu.php');
require('/home/thesis-std/support/bpaltmetrics/database.php');
//Help mouseover:
$mouseOverString = "Select for which person you want to find papers. Be advised: This can take quite a long time.";
include('tooltip.php');

$persons = retrieve_persons($con);
?>
<form action='find_papers_get.php' method='post'>
    <select name="pId">
        <?php
        foreach($persons as $person) {
            echo "<option value='".$person["pId"]."'>".$person["name"]." ".$person["lastName"]."</option>";
        }
        ?>
    </select></br>
    <input type="submit" value="Select"/>
    </br><small><a href = "manual_papers.pdf" target="_blank">Manual</a></small>
    </br><small>Note: due to difficulties to access the ACM website, you cannot include papers of that network.</small>
    
    

<?php

include('/home/thesis-std/support/bpaltmetrics/footer.php');
?>
