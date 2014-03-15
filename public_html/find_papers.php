<?php
ini_set('display_errors', 1);

$page_title = "find papers";
include('/home/thesis-std/menu.php');
require('/home/thesis-std/database.php');

$persons = retrieve_persons($con);
?>
<form action='find_papers_get.php' method='post'>
    <select name="pId">
        <?php
        foreach($persons as $person) {
            echo "<option value='".$person["pId"]."'>".$person["name"]." ".$person["lastName"]."</option>";
        }
        ?>
    </select></br>
    <input type="submit" value="Select"/>
    

<?php
include('/home/thesis-std/footer.php');
?>
