<?
$page_title = "visualisation whole network: select parameter";
include('menu.php');
include('database.php');
//Help mouseover:
$mouseOverString = "Select the parameter you want to compare. You can choose to include people who do not have data for every possible network.";
include('tooltip.php');


?>
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
    <input type="checkbox" name="include_missing">Include people with missing values.</br>
    <input type="hidden" value="<?php echo urlencode(serialize($metrics)) ?>" name="metrics" />
    <input type="submit" value="Select"/>
    
</form>

<?php include('footer.php') ?>
