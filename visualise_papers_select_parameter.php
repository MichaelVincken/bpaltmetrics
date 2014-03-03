<?php
$page_title = "visualise papers";
require('menu.php');
require('database.php');

$persons = retrieve_persons_with_papers($con);
?>
<form action='visualise_papers_end.php' method='post'>
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
