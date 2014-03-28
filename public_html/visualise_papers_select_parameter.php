<?php
$page_title = "visualise papers";
require('/home/thesis-std/menu.php');
require('/home/thesis-std/database.php');
//Help mouseover:
$mouseOverString = "Choose whose papers you want to have a look at. Only those who have papers in the database can be chosen. If you want someone else, you first have to 'find' his papers.";
include('tooltip.php');

$persons = retrieve_persons_with_papers($con);
?>
<form action='visualise_papers_select_networks.php' method='post'>
    <select name="pId">
        <?php
        foreach($persons as $person) {
            echo "<option value='".$person["pId"]."'>".$person["name"]." ".$person["lastName"]."</option>";
        }
        ?>
    </select></br>
    <input type="submit" value="Select"/>


<?php
require('/home/thesis-std/footer.php');
?>
