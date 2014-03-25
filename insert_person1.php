
<?php
$page_title = "Insert Person";
include('menu.php');
include('database.php');
//Help mouseover:
$mouseOverString = "Give the name and family name of the person you seek.";
include('tooltip.php');

$networks = urlencode(serialize(retrieve_networks($con)));
?>
<form action="insert_person2.php" method="post">
    First name: <input type="text" placeholder= "given name" name="firstname" autofocus="autofocus"><br>
    Last name: <input type="text" placeholder= "family name" name="lastname"><br>
       
    <input type="hidden" value="<?php echo $networks ?>" name="networks" />
        
    <input type="submit" value="submit"/>
</form>
<?php
include('footer.php');
?>
