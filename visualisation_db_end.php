<?
$page_title = "visualisation whole network";
include('menu.php');
include('database.php');

$metrics = unserialize(urldecode($_POST["metrics"]));
$metric = ($_POST["metric"]);
$include_missing=isset($_POST['include_missing']);
?>
<h3>Change parameter.</h3>
<form action='visualisation_db_end.php' method='post'>
    <select name="metric">
        <?php
        foreach($metrics as $metric_opt) {
            echo "<option value='".$metric_opt."'>".ucwords(str_replace("_"," ",$metric_opt))."</option>";
        }
        ?>
    </select></br>
    <input type="checkbox" name="include_missing">Include people with missing values.</br>
    <input type="hidden" value="<?php echo urlencode(serialize($metrics)) ?>" name="metrics" />
    <input type="submit" value="Select"/>
    
</form>
<h3>Visualization: <?php echo $metric?></h3>
<?php
$include_string = ($include_missing)? "1" : "0";
$data_location = "visualise_db_data.php?include_missing=".$include_string."&metric=".$metric;
include('parallel.php');

require('footer.php');    
?>
