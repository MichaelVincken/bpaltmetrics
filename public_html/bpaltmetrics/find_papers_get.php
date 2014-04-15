<?php   
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
$page_title = "find papers";
include('/home/thesis-std/support/bpaltmetrics/menu.php');
include('/home/thesis-std/support/bpaltmetrics/database.php');
require('/home/thesis-std/support/bpaltmetrics/scrape.php');
//Help mouseover:
$mouseOverString = "Getting the papers from the networks. This can take a while.";
include('tooltip.php');

?>
<?php
//ERROR if this page is loaded without first going trough the previous pages: redirect.
if(!isset($_POST["pId"])) {
    echo '<meta http-equiv="refresh" content="0;URL=find_papers.php" />';
    exit;
}
try {
$pId = ($_POST["pId"]);
//If the networks array is not set, we first get the networks array from database.php and then get all the papers that are currently in the network. //Then continue to this page for the next network (which by the way is the first, because none of them have been searched.)
if(!isset($_POST["networks"])) {
    $networks = retrieve_networks($con);
    $networks = array_diff($networks, array('acm'));

    $papers = array();
    //first: finding all papers that are currently in the network. :(
    //$papers["current"] = retrieve_papers($con);
    
    echo "Beginning search of network. Number of networks: ".count($networks);
    flush();
    ?>
    <form name="next_form" action="find_papers_get.php" method="post">
        <input type="hidden" value="<?php echo $pId?>" name ="pId" />
        <input type="hidden" value="<?php echo urlencode(serialize($networks))?>" name="networks" />
        <input type="hidden" value="<?php echo urlencode(serialize($papers))?>" name="papers" />
        <!-- <input type="submit" value="submit"/> -->
            
    </form>
    
    <script language = "JavaScript">
    document.next_form.submit();
    </script> 
    
    <?php

} else {
    //Check if the number of networks == 0 --> we need to continue to the next page (find_papers_compare);
    $networks = unserialize(urldecode($_POST["networks"]));
    $papers = unserialize(urldecode($_POST["papers"]));
        
    if(count($networks) == 0) {
        //We are done and advance to the next network.
        ?>
        Getting the papers from the different networks is done.
        Please be patient, all papers will be compared to find matching ones across networks.
        
        <form name="next_form" action="find_papers_compare.php" method="post">
            <input type="hidden" value="<?php echo $pId?>" name ="pId" />
            <input type="hidden" value="<?php echo urlencode(serialize($papers))?>" name="papers" />
            
        <!-- <input type="submit" value="submit"/> -->
            
        </form>
        <?php
    } else {
        //We are not done and get the papers for the next network.
        $network = array_shift($networks);
        echo (count($networks)+1)." networks to go. Getting papers of the ".$network." network";
        flush();
        try{
            $urls = retrieve_urls($pId,$network,$con);
        } catch (Exception $e) {
                echo "<script>";
                echo "window.alert('could not reach: ".$network.")";
                echo "</script>";
        }
        ?>
        
        <form name="next_form" action="find_papers_get_scrape.php" method="post">
            <input type="hidden" value="<?php echo $pId?>" name ="pId" />
            <input type="hidden" value="<?php echo $network?>" name ="network" />
            <input type="hidden" value="<?php echo urlencode(serialize($networks))?>" name="networks" />
            <input type="hidden" value="<?php echo urlencode(serialize($urls))?>" name="urls" />
            <input type="hidden" value="<?php echo urlencode(serialize($papers))?>" name="papers" />
            
        <!-- <input type="submit" value="submit"/> -->
        </form>
        <?php
           
    }
}
flush();
?>
<script language = "JavaScript">
document.next_form.submit();
</script> 
<?php
} catch (Exception $e) {
    echo "something went wrong.";
}


?>
    