<?php
$page_title = "visualise papers";
require('/home/thesis-std/menu.php');
require('/home/thesis-std/database.php');

$persons = retrieve_persons_with_papers($con);
?>
<form action='visualise_papers_end.php' method='post'>
    <select name="pId">
        <?php
        foreach($persons as $person) {
            echo "<option value='".$person["pId"]."'>".$person["name"]." ".$person["lastName"]."</option>";
        }
        ?>
    </select></br>
    <input type="submit" value="Select"/>
    

<?php
require('/home/thesis-std/footer.php');
?>
