<?php
//Redirect to home page if not right password.
if(!isset($_GET["password"]) || $_GET["password"]!="PassWord") {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit;
}
require("/home/thesis-std/database.php");
$persons = retrieve_persons($con);
?>
<table>
    <tr>
        <th>Firstname</th>
        <th>LastName</th>
    </tr>
<?php
foreach($persons as $person) {
    echo "<tr>";
    echo "<td>".$person["name"]."</td>";
    echo "<td>".$person["lastName"]."</td>";
    ?>
    <td>
        <form name="delete_form" action="delete_person.php" method="post">
            <input type="hidden" value="<?php echo $person['pId']?>" name ="pId" />
            <input type="submit" value="Delete"/>
        </form>
<?php
}
?>


