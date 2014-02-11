<?php
$page_title = "Insert Person";
include('menu.php');
include('scrape.php');
$firstname =  mysql_real_escape_string($_POST["firstname"]);
$lastname =  mysql_real_escape_string($_POST["lastname"]);
$url = mysql_real_escape_string($_POST["url"]);
$result = google_scrape::get_person($url);
$resultstring = urlencode(serialize($result));
$networks = mysql_real_escape_string($_POST["networks"]);
        
?>
<form name = "frm" action="insert_person3.php" method = "post" >
    <input type="hidden" value="<?php echo $resultstring ?>" name="result" />
    <input type="hidden" value="<?php echo $url ?>" name="url" />
    <input type="hidden" value="<?php echo $firstname ?>" name="firstname" />
    <input type="hidden" value="<?php echo $lastname ?>" name="lastname" />
    <input type="hidden" value="<?php echo $networks ?>" name="networks" />
</form>
<script language = "JavaScript">
document.frm.submit();
</script>
<tbody>

<?php include("footer.php")?>