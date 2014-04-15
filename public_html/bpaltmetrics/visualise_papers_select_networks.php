<?php
$page_title = "visualise papers";
require('/home/thesis-std/support/bpaltmetrics/menu.php');
require('/home/thesis-std/support/bpaltmetrics/database.php');
$pId = ($_POST["pId"]);
//Help mouseover:
$mouseOverString = "Choose the networks you want to compare. It is important to note that more than three networks is not ideal for the graphical representation (venn diagram).";
include('tooltip.php');

//ERROR if this page is loaded without first going trough the previous pages: redirect.
if(!isset($_POST["pId"])) {
    echo '<meta http-equiv="refresh" content="0;URL=visualise_papers_select_parameter.php" />';
    exit;
}



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
require('/home/thesis-std/support/bpaltmetrics/footer.php');
?>
