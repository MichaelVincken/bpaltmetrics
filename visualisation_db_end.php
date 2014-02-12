<?
$page_title = "visualisation whole network";
include('menu.php');
include('database.php');

$metrics = mysql_real_escape_string($_POST["metrics"]);
$metric = mysql_real_escape_string($_POST["metric"]);

?>
<h3>Change parameter.</h3>
<form action='visualize.php' method='post'>
    <select name="metric">
        <?php
        foreach($metrics as $metric) {
            echo "<option value='".$metric."'>".ucwords(str_replace("_"," ",$metric))."</option>";
        }
        ?>
        <input type="hidden" value="<?php echo $metrics ?>" name="metrics" />
        <input type="submit" value="Select"/>
    </select>
</form>
<h3>Visualization:</h3>
<?php
    
    
?>
