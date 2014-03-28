<?php   
$page_title = "find papers";
include('/home/thesis-std/menu.php');
require('/home/thesis-std/database.php');
require('/home/thesis-std/scrape.php');
//Help mouseover:
$mouseOverString = "Comparing all papers with the papers in the database to estabilish which of them are the same.";
include('tooltip.php');

?>

<?php
//ERROR if this page is loaded without first going trough the previous pages: redirect.
if(!isset($_POST["pId"])) {
    echo '<meta http-equiv="refresh" content="0;URL=find_papers.php" />';
    exit;
}
$papers = unserialize(urldecode(($_POST["papers"])));
//var_dump(count($papers["microsoft"]));
$pId = ($_POST["pId"]);
$networks = array_keys($papers);
$similar_papers = array();
//Traverse trough all networks
for($i=0;$i<count($networks);$i++) {
    $papers_network = $papers[$networks[$i]];
    //for every paper in the network
    for($p=0;$p<count($papers_network);$p++) {
        //if it is not deleted.
        if(isset($papers[$networks[$i]][$p])) {
            $paper = $papers[$networks[$i]][$p];
            //array waarin alle similar papers worden bijgehouden.
            $similar_array = array();
            //for every network following this network and also this network to find doubles here.
            for($j=$i;$j<count($networks);$j++) {
                $papers_other = $papers[$networks[$j]];
                //for each paper in that following network: compare to current $paper -> similar($paper,$other)
                for($o=0;$o<count($papers_other);$o++) {
                    //if it is not deleted.
                    if(isset($papers_other[$o]) && $papers_other[$o] !== $paper) {
                        $other = $papers_other[$o];
                        //compare
                        //text is very similar.
                        similar_text($paper["title"],$other["title"],$percentage);
                        if(($percentage > 85)) {
                            array_push($similar_array,$other);
                            unset($papers[$networks[$j]][$o]);
                        }
                    }
                }
            }
            array_push($similar_array,$paper);
            array_push($similar_papers,$similar_array);
        }
    }
    
}

//var_dump(count($similar_papers));
?>

Please be patient, all papers need to be inserted in the databse.
<?php include("spinnner.php"); flush();?>

<form name="auto_form" action="find_papers_end.php" method="post">
    <input type="hidden" value="<?php echo urlencode(serialize($similar_papers))?>" name="papers" />
    <input type="hidden" value="<?php echo $pId?>" name ="pId" />
        <!-- ><input type="submit" value="submit"/> -->
            
</form>
<script language = "JavaScript">
document.auto_form.submit();
</script> 

<?php require_once('/home/thesis-std/footer.php');    ?>



