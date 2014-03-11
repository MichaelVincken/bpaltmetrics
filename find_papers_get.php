
<?php   
$page_title = "find papers";
require('menu.php');
require('database.php');
require('scrape.php');
?>

<?php
$pId = ($_POST["pId"]);
$networks = retrieve_networks($con);
$papers = array();
//first: finding all papers that are currently in the network. :(
$paper["current"] = retrieve_papers($con);


//Add for each network the found papers of this person. 
foreach($networks as $network) {
    $urls = retrieve_urls($pId,$network,$con);
    foreach($urls as $url) {
        try{
        $papers[$network] = call_user_func_array($network.'_scrape'.'::search_papers',array($url));
    } catch (Exception $e) {
            echo "<script>";
            echo "window.alert('could not reach: ".$network.")";
            echo "</script>";
        }
    }
}

?>
Please be patient, all papers will be compared to find matching ones acros networks.
<form name="auto_form" action="find_papers_compare.php" method="post">
    <input type="hidden" value="<?php echo urlencode(serialize($papers))?>" name="papers" />
    <input type="hidden" value="<?php echo $pId?>" name ="pId" />
    <input type="submit" value="submit"/>
            
</form>
<script language = "JavaScript">
document.auto_form.submit();
</script> 
    
?>