<?php
$page_title = "find papers: database.";
require_once('/home/thesis-std/support/bpaltmetrics/menu.php');
require_once('/home/thesis-std/support/bpaltmetrics/database.php');
//Help mouseover:
$mouseOverString = "Inserting papers into the database. If this is done, you can go to any page you want.";
include('tooltip.php');

//ERROR if this page is loaded without first going trough the previous pages: redirect.
if(!isset($_POST["pId"])) {
    echo '<meta http-equiv="refresh" content="0;URL=find_papers.php" />';
    exit;
}

$personId = ($_POST["pId"]);
$papers = unserialize(urldecode($_POST["papers"]));
var_dump(count($papers));

//For every paper we found. $paper is an array of entrys for 1 paper.
foreach($papers as $paper) {
    //For each paper_array, we first check if we have an existing paper or a new one.
    if(isset($paper[0]["current"])) {
        $paperId = $paper[0]["pId"];
        insert_authored($paperId,$personId,$con);
        
        for($i=1;$i<count($paper);$i++) {
            $network = $entry["network"];
            $url = $entry["url"];
            insert_paper_url($network,$paperId,$url,$con);
            //Insert new tuple for the citations.
            $citations = $entry["citations"];   
            $table_name = $network."_paper";
            $query_citation = "INSERT INTO {$table_name} VALUES ('{$paperId}', CURDATE(), '{$citations}')";
            try {
                mysqli_query($con,$query_citation);
                                
            } catch (Exception $e) {
                echo "Oops, something went wrong while inserting this paper.";
            }
        }
    } else {
        //insert new paper in the database. This also returns an existing paper IF it was already in it.
        $title = $paper[0]["title"];
        $result = select_paper($title,$con);
        $paperId = $result[0]["pId"];
        //insert new authored tuple.
        insert_authored($paperId,$personId,$con);
        foreach($paper as $entry) {
            $network = $entry["network"];
            $url = $entry["url"];
            insert_paper_url($network,$paperId,$url,$con);
            //Insert new tuple for the citations.
            $citations = $entry["citations"];   
            $table_name = $network."_paper";
            $query_citation = "INSERT INTO {$table_name} VALUES ('{$paperId}', CURDATE(), '{$citations}')";
            
            try {
                mysqli_query($con,$query_citation);
                                
            } catch (Exception $e) {
                echo "Oops, something went wrong while inserting this paper.";
            }
            
        }
        
    }
}
echo "all papers where inserted in the database.";    
echo '<meta http-equiv="refresh" content="0;URL=visualise_papers_select_parameter.php" />';
    
    
require_once('/home/thesis-std/support/bpaltmetrics/footer.php');   

 
?>