<?php
$page_title = "visualise papers";
require('/home/thesis-std/support/bpaltmetrics/menu.php');
require('/home/thesis-std/support/bpaltmetrics/database.php');
//Help mouseover:
$mouseOverString = "Choose whose papers you want to have a look at. Only those who have papers in the database can be chosen. If you want someone else, you first have to find his papers.";
include('tooltip.php');

$persons = retrieve_persons_with_papers($con);
?>
<form action='visualise_papers_select_networks.php' method='post'>
    <select name="pId">
        <?php
        foreach($persons as $person) {
            echo "<option value='".$person["pId"]."'>".$person["name"]." ".$person["lastName"]."</option>";
        }
        ?>
    </select></br>
    <input type="submit" value="Select"/>
</br><small><a href = "manual_papers.pdf" target="_blank">Manual</a></small>
</br><small>Note: new searches for papers do not include the ACM network. If you can select ACM on the next page, be aware that those are papers that incidentaly were already inserted into the database by someone else.</small>
</br>For the application to be able to visualise your papers, you have <a href="find_papers.php">to insert them in the database first.</a>


<?php
require('/home/thesis-std/support/bpaltmetrics/footer.php');
?>
