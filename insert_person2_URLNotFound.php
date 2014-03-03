<?php
$page_title = "Insert Person";
include('menu.php');
include('scrape.php');
$firstname =  ($_POST["firstname"]);
$lastname =  ($_POST["lastname"]);
$url = ($_POST["url"]);
$result = google_scrape::get_person($url);
$resultstring = urlencode(serialize($result));
$networks = ($_POST["networks"]);
        
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