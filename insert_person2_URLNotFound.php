<?php
$page_title = "Insert Person";
include('menu.php');
include('scrape.php');
//Help mouseover:
$mouseOverString = "Inserting the right person with the given url into the database. You don't have to do anything.";
include('tooltip.php');

$firstname =  ($_POST["firstname"]);
$lastname =  ($_POST["lastname"]);
$url = ($_POST["url"]);
$networks = unserialize(urldecode($_POST["networks"]));
$network_name = $networks[0];
$result = call_user_func_array($network_name.'_scrape'.'::get_person',array($url));
$resultstring = urlencode(serialize($result));
        
?>
<form name = "frm" action="insert_person3.php" method = "post" >
    <!-- Needed: checkboxes-tag-->
    <?php
    $checkboxes = array();
    $checkboxes[0] = TRUE;
    ?>
    <input type="hidden" value="<?php echo urlencode(serialize($checkboxes))?>">
    
    <input type="hidden" value="<?php echo $resultstring ?>" name="persons[]" />
    <input type="hidden" value="<?php echo $url ?>" name="urls[]" />
    <input type="hidden" value="<?php echo $firstname ?>" name="firstnames[]" />
    <input type="hidden" value="<?php echo $lastname ?>" name="lastnames[]" />
    <input type="hidden" value="<?php echo urlencode(serialize($networks)) ?>" name="networks[]" />
    <input type="submit" value="confirm">
</form>
<script language = "JavaScript">
document.frm.submit();
</script>
<tbody>

<?php include("footer.php")?>