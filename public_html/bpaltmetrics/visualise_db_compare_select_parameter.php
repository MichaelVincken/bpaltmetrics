<?php
$page_title="Compare people.";
require('/home/thesis-std/support/bpaltmetrics/database.php');
require('/home/thesis-std/support/bpaltmetrics/menu.php');

//ERROR if this page is loaded without first going trough the previous pages: redirect.
if(!isset($_POST["persons"])) {
    echo '<meta http-equiv="refresh" content="0;URL=visualise_db_compare_select_persons.php" />';
    exit;
}

$mouseOverString = "Select the parameter you want to compare.";
include('tooltip.php');


$persons = $_POST["persons"];
?>
<h3>Select the Metric you want to use to visualize the database.</h3>
<form action='visualise_db_compare_end.php' method='post'>
    <select name="metric">
        <?php
        $metrics = get_all_comparable_metrics($con);
        foreach($metrics as $metric) {
            echo "<option value='".$metric."'>".ucwords(str_replace("_"," ",$metric))."</option>";
        }
        ?>
    </select></br>
    <input type="hidden" value="<?php echo urlencode(serialize($metrics)) ?>" name="metrics" />
    <input type="hidden" value="<?php echo urlencode(serialize($persons)) ?>" name="persons" />
    
    <input type="submit" value="Select"/>
    
</form>
