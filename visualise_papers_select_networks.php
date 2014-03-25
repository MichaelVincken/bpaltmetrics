<?php
$page_title = "visualise papers";
require('menu.php');
require('database.php');
$pId = ($_POST["pId"]);
//Help mouseover:
$mouseOverString = "Choose the networks you want to compare. It is important to note that more than three networks is not ideal for the graphical representation (venn diagram).";
include('tooltip.php');

$networks = retrieve_networks_person($pId,$con);
?>
<h3>Select the networks you want to compare</h3>
<form action='visualise_papers_end.php' method='post'>
    <input type="hidden" value="<?php echo $pId?>" name ="pId" />
    <?php
    foreach($networks as $network) {
        echo '<input type ="checkbox"" name="networks[]" value="'.$network.'"/>'.$network."<br />";
    }
    ?>
    
    <input type="submit" value="Select"/>
</form>

<?php
require('footer.php');
?>
