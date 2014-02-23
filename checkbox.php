<?php
$page_title = "Checkbox";
require_once('menu.php');
require_once('database.php');

$checkboxes = isset($_POST['persons']) ? $_POST['persons'] : array();
$numberOfPersons = count($checkboxes);
$resultPersons = array();
foreach($checkboxes as $person) {
    array_push($resultPersons, unserialize(urldecode($person)));
}

var_dump($resultPersons);

// TODO: Itereren over alle metrices
// Indien er meerdere personen werden geselecteerd
// moeten de metrieken samengeteld worden

$firstname =  mysql_real_escape_string($_POST["firstname"]);
$lastname =  mysql_real_escape_string($_POST["lastname"]);
$url = mysql_real_escape_string($_POST["url"]);
$result_string =  mysql_real_escape_string($_POST["result"]);
$result_array = unserialize(urldecode($result_string));

//Get the right pId or make a new "person"
$person_array = select_person($firstname,$lastname,$con);
$pId = $person_array[0]["pId"];


//Specifics for the current network.        
$network_string =  mysql_real_escape_string($_POST["networks"]);
$network_array = unserialize(urldecode($network_string));
$network_name = array_shift($network_array);      

//Insert URL in database for current network
insert_url($pId,$network_name,$url,$con);

//column_array has all fields/metrics of the current network        
$column_array = retrieve_columns($network_name.'_person',$con);  
 
//Building query_string        
$query_string = "'{$pId}', Curdate()";
for ($i = 2; $i < count($column_array); $i++) {
    $query_string = $query_string . ",'{$result_array[$column_array[$i]]}'";
}
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
<!-- Form to get information to next page.-->
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