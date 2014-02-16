<?
$page_title = "visualisation whole network";
include('menu.php');
include('database.php');

$metrics = unserialize(urldecode($_POST["metrics"]));
$metric = mysql_real_escape_string($_POST["metric"]);
var_dump($metric);
$include_missing=isset($_POST['include_missing']);
var_dump($include_missing);
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
<h3>Visualization:</h3>
<?php
$include_string = ($include_missing)? "0" : "1";
$data_location = "visualise_db_data.php?include_missing=".$include_string."&metric=".$metric;
include('parallel.php');

    
?>
