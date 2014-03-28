<?php
if(!isset($_POST["pId"])) {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit;
}

$pId = $_POST["pId"];
require("/home/thesis-std/database.php");
delete_person($pId,$con);
echo '<meta http-equiv="refresh" content="0;URL=admin.php?password=PassWord" />';

?>