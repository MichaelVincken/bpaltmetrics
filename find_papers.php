<?php
$page_title = "find papers";
require('menu.php');
require('database.php');
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
    

<?php
require('footer.php');
?>
