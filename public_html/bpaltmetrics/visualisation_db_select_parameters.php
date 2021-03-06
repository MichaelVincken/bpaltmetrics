<?php
$page_title = "visualisation whole network: select parameter";
include('/home/thesis-std/support/bpaltmetrics/menu.php');
include('/home/thesis-std/support/bpaltmetrics/database.php');
//Help mouseover:
$mouseOverString = "Select the parameter you want to compare. You can choose to include people who do not have data for every possible network.";
include('tooltip.php');


?>
<style type='text/css'>
    .tooltip:hover:after {content:"Some people might not have data for every network. If you check this, you include them in the visualisation. This results in incomplete lines.";}
</style>



<h3>Select the Metric you want to use to visualize the database.</h3>
<form action='visualisation_db_end.php' method='post'>
    <select name="metric">
        <?php
        $metrics = get_all_comparable_metrics($con);
        foreach($metrics as $metric) {
            echo "<option value='".$metric."'>".ucwords(str_replace("_"," ",$metric))."</option>";
        }
        ?>
    </select></br>
    <input type="checkbox" name="include_missing"><div class='tooltip'>Include people with missing values.</div></br>
    <input type="hidden" value="<?php echo urlencode(serialize($metrics)) ?>" name="metrics" />
    <input type="submit" value="Select"/>
    
</form>
</br><small><a href = "manual_metrics.pdf" target="_blank">Manual</a></small>

<?php include('/home/thesis-std/support/bpaltmetrics/footer.php') ?>
