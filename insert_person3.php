<?php
$page_title = "Insert Person";
require_once('login.php');
require_once('menu.php');
require_once('database.php');
        
$firstname =  mysql_real_escape_string($_POST["firstname"]);
$lastname =  mysql_real_escape_string($_POST["lastname"]);
$url = mysql_real_escape_string($_POST["url"]);
$result_string =  mysql_real_escape_string($_POST["result"]);
$result_array = unserialize(urldecode($result_string));
$person_array = select_person($firstname,$lastname,$con);
$pId = $person_array[0]["pId"];
        
$network_string =  mysql_real_escape_string($_POST["networks"]);
$network_array = unserialize(urldecode($network_string));
$network_name = array_shift($network_array)[0];      
        
$column_array = retrieve_columns($network_name.'_person',$con);  
        
$query_string = "'{$pId}', '{$url}', Curdate()";
for ($i = 3; $i < count($column_array); $i++) {
    $query_string = $query_string . ",'{$result_array[$column_array[$i]]}'";
}
//var_dump($query_string);
//var_dump($column_array);
//var_dump($result_array);
//var_dump($network_name);
//var_dump($network_array);
        
$table_name = $network_name."_person";
$query = "INSERT INTO {$table_name} VALUES ({$query_string})" ;
                
try {
    mysqli_query($con,$query);
    echo "Person succesfully inserted in the database.";
} catch (Exception $e) {
    echo "Person has already been inserted in the database.";
}   
?>
        
<br/>
Please wait while searching next network.
<form name="auto_form" action="insert_person2.php" method="post">
    <input type="hidden" value="<?php echo $firstname ?>" name="firstname" />
    <input type="hidden" value="<?php echo $lastname ?>" name="lastname" />
    <input type="hidden" value="<?php echo urlencode(serialize($network_array))?>" name="networks" />
            
</form>
<script language = "JavaScript">
document.auto_form.submit();
</script> 
            
        
<?php include('footer.php')?>