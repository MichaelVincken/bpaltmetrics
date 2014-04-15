<?php
ini_set('display_errors', 1);

$page_title = "Compare people";
include('/home/thesis-std/support/bpaltmetrics/menu.php');
include('/home/thesis-std/support/bpaltmetrics/database.php');
//Help mouseover:
$mouseOverString = "This page is a parallell coordinate representation of the people you selected. You can change the order of the different axes. By dragging over a specific axis you can focus your attention on those you selected.";
include('tooltip.php');

//ERROR if this page is loaded without first going trough the previous pages: redirect.
if(!isset($_POST["metrics"])) {
    echo '<meta http-equiv="refresh" content="0;URL=visualise_db_compare_select_persons.php" />';
    exit;
}
$persons = unserialize(urldecode($_POST["persons"]));
$metrics = unserialize(urldecode($_POST["metrics"]));
$metric = ($_POST["metric"]);
$include_missing=True;
?>

<h3>Change parameter.</h3>
<form action='visualise_db_compare_end.php' method='post'>
    <select name="metric">
        <?php
        foreach($metrics as $metric_opt) {
            echo "<option value='".$metric_opt."'>".ucwords(str_replace("_"," ",$metric_opt))."</option>";
        }
        ?>
    </select></br>
    <input type="hidden" value="<?php echo urlencode(serialize($metrics)) ?>" name="metrics" />
    <input type="hidden" value="<?php echo urlencode(serialize($persons)) ?>" name="persons" />
    
    <input type="submit" value="Select"/>
    
</form>
<h3>Visualization: Compare people: <?php echo $metric?></h3>
<?php
$data_location = "visualise_db_data.php?include_missing=1&metric=".$metric."&persons=".urlencode(serialize($persons));

include('parallel.php');

require('/home/thesis-std/support/bpaltmetrics/footer.php');    
?>



