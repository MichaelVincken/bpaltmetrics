<?
$page_title = "visualisation whole network: select parameter";
include('menu.php');
include('database.php');

?>
<h3>Select the Metric you want to use to visualize the database.</h3>
<form action='visualize.php' method='post'>
    <select name="metric">
        <?php
        foreach(get_all_comparable_metrics($con) as $metric) {
            echo "<option value='".$metric."'>".ucwords(str_replace("_"," ",$metric))."</option>";
        }
        ?>
        <input type="hidden" value="<?php echo $metrics ?>" name="metrics" />
        <input type="submit" value="Select"/>
    </select>
</form>

<?php include('footer.php') ?>
