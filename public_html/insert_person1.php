
<?php
$page_title = "Insert Person";
include('/home/thesis-std/menu.php');
include('/home/thesis-std/database.php');
$networks = urlencode(serialize(retrieve_networks($con)));
?>
lala
<form action="insert_person2.php" method="post">
    First name: <input type="text" placeholder= "given name" name="firstname" autofocus="autofocus"><br>
    Last name: <input type="text" placeholder= "family name" name="lastname"><br>
       
    <input type="hidden" value="<?php echo $networks ?>" name="networks" />
        
    <input type="submit" value="submit"/>
</form>
<?php
include('/home/thesis-std/footer.php');
?>
