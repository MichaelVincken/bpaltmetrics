<?php
$page_title = "visualisation whole network";
include('/home/thesis-std/support/bpaltmetrics/menu.php');
include('/home/thesis-std/support/bpaltmetrics/database.php');
//Help mouseover:
$mouseOverString = "This page is a parallell coordinate representation of all people and networks in the database. You can change the order of the different axes. By dragging over a specific axis you can focus your attention on those you selected.";
include('tooltip.php');

//ERROR if this page is loaded without first going trough the previous pages: redirect.
if(!isset($_POST["metrics"])) {
    echo '<meta http-equiv="refresh" content="0;URL=visualisation_db_select_parameters.php" />';
    exit;
}


$metrics = unserialize(urldecode($_POST["metrics"]));
$metric = ($_POST["metric"]);
$include_missing=isset($_POST['include_missing']);
?>
<style type='text/css'>
    .tooltip:hover:after {content:"Some people might not have data for every network. If you check this, you include them in the visualisation. This results in incomplete lines.";}
</style>




<h3>Change parameter.</h3>
<form action='visualisation_db_end.php' method='post'>
    <select name="metric">
        <?php
        foreach($metrics as $metric_opt) {
            echo "<option value='".$metric_opt."'>".ucwords(str_replace("_"," ",$metric_opt))."</option>";
        }
        ?>
    </select></br>
    <input type="checkbox" name="include_missing"><div class='tooltip'>Include people with missing values.</div></br>
    <input type="hidden" value="<?php echo urlencode(serialize($metrics)) ?>" name="metrics" />
    <input type="submit" value="Select"/>
    
</form>
<h3>Visualization: <?php echo $metric?></h3>
<?php
$include_string = ($include_missing)? "1" : "0";
$data_location = "visualise_db_data.php?include_missing=".$include_string."&metric=".$metric;

include('parallel.php');

require('/home/thesis-std/support/bpaltmetrics/footer.php');    
?>
