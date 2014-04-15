
<?php
$page_title = "Insert Person";
include('/home/thesis-std/support/bpaltmetrics/menu.php');
include('/home/thesis-std/support/bpaltmetrics/database.php');
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
<small>Note: The server is going to search the different networks for you. This can take quite a while, so be patient.</small>
</br><small><a href = "manual_metrics.pdf" target="_blank">Manual</a></small>
<?php
include('/home/thesis-std/support/bpaltmetrics/footer.php');
?>
