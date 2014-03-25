<?php
$page_title = "Checkbox";
require_once('menu.php');
require_once('database.php');
//Help mouseover:
$mouseOverString = "Busy entering data into database. You don't have to do anything.";
include('tooltip.php');

$checkboxes = isset($_POST['persons']) ? $_POST['persons'] : array();
$numberOfPersons = count($checkboxes);
$resultPersons = array();
foreach($checkboxes as $person) {
    array_push($resultPersons, unserialize(urldecode($person)));
}


// If the user selected multiple persons
// if-else mag straks weg wanneer straks de if volledig dynamisch werkt
$firstname = ($_POST["firstnames"][0]);
$lastname = ($_POST["lastnames"][0]);
    
// Retrieve all the information from POST
for($i=0;$i<$numberOfPersons;$i++) {
    $url[$i] = ($_POST["urls"][$i]);
}

//Retrieve pId:
$person_array = select_person($firstname,$lastname,$con);
$pId = $person_array[0]["pId"];
           
// Specifics for the current network for this person

$network_string =  ($_POST["networks"][0]);
$network_array = unserialize(urldecode($network_string));
$network_name = array_shift($network_array);


// Inserting all i-urls from i-selected persons
for($i=0;$i<$numberOfPersons;$i++) {
    insert_url($pId,$network_name,$url[$i],$con);
}

$column_array = retrieve_columns($network_name.'_person',$con);

    
//Build the query_string
$query_string = "'{$pId}', Curdate()";
for ($i = 2; $i < count($column_array); $i++) {
    //name of the column
    $column = $column_array[$i];
    //special cases: calc something
    if($column == "citations" || $column == "publications") {
        //sum of all values.
        $value = 0;
        foreach($resultPersons as $person) {
            $value = $value + $person[$column];
        }
        $query_string = $query_string.", '{$value}'";       
    } elseif($column == "h-index" || $column == "i10-index") {
        //Get max
        $value = 0;
        foreach($resultPersons as $person) {
            if($person[$column] > $value) {
                $value = $person[$column];
            }
        }
        $query_string = $query_string.", '{$value}'";       
    } else {
        //base case: just get the first one.
        $query_string = $query_string . ",'{$resultPersons[0][$column]}'";
    }
}
$table_name = $network_name."_person";
$query = "INSERT INTO {$table_name} VALUES ({$query_string})";
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
<?php

include('footer.php')
    ?>