<?php
$page_title = "find papers: database.";
require_once('menu.php');
require_once('database.php');
$papers = unserialize(urldecode(mysql_real_escape_string($_POST["papers"])));
$personId = mysql_real_escape_string($_POST["pId"]);
//For every paper we found. $paper is an array of entrys for 1 paper.
foreach($papers as $paper) {
    $con = mysqli_connect('p:84.246.4.143','StappaertsDB','Databases1','stappaertsdb',9132) or die('Verbinding naar externe mysqldb gefaald!');
    //For each paper_array, we first check if we have an existing paper or a new one.
    if(isset($paper[0]["current"])) {
        $paperId = $paper[0]["pId"];
        $query = "INSERT INTO authored VALUES ('{$paperId}','{$personId}')";
        mysqli_query($con,$query);
        
        for($i=1;$i<count($paper);$i++) {
            $network = $paper[$i]["network"];
            $citations = $paper[$i]["citations"];
            $url = $paper[$i]["url"];
            $table_name = $network."_url_paper";
            $query_url = "INSERT INTO {$table_name} VALUES ('{$paperId}','{$url}')";
            
            $table_name = $network."_paper";
            $query_citation = "INSERT INTO {$table_name} VALUES ('{$paperId}', CURDATE(), '{$citations}')";
            try {
                mysqli_query($con,$query_url);
                mysqli_query($con,$query_citation);
                                
            } catch (Exception $e) {
                echo "Oops, something went wrong while inserting this paper.";
            }
        }
    } else {
        $title = $paper[0]["title"];
        $query = "INSERT INTO paper VALUES (DEFAULT, '{$title}') ";
        mysqli_query($con,$query);
        $query = "SELECT pId FROM paper WHERE title = '{$title}'";
        $result = mysqli_query($con,$query);
        $paperId = mysqli_fetch_array($result)["pId"];
        $query = "INSERT INTO authored VALUES ('{$paperId}','{$personId}')";
        mysqli_query($con,$query);
        
        
        foreach($paper as $entry) {
            $network = $entry["network"];
            $citations = $entry["citations"];
            $url = $entry["url"];
            $table_name = $network."_url_paper";
            $query_url = "INSERT INTO {$table_name} VALUES ('{$paperId}','{$url}')";
            
            $table_name = $network."_paper";
            $query_citation = "INSERT INTO {$table_name} VALUES ('{$paperId}', CURDATE(), '{$citations}')";
            try {
                mysqli_query($con,$query_url);
                mysqli_query($con,$query_citation);
                                
            } catch (Exception $e) {
                echo "Oops, something went wrong while inserting this paper.";
            }
            
        }
        
    }
}
echo "all papers where inserted in the database.";    
    
    
require_once('footer.php');    
?>