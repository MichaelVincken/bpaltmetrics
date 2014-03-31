        <style type='text/css'>
            #nav {opacity:0.4;}
        </style>

<?php
$page_title = 'Insert Person';
        
include_once('/home/thesis-std/menu.php');
require_once('/home/thesis-std/scrape.php');
require_once('/home/thesis-std/database.php');

//Help mouseover:
$mouseOverString = "Please choose one of the appropriate options. Do not forget the check if the entries you choose are right for the person you seek. You can visit the found webpages by clicking on a name. You can also skip this database.";
include('tooltip.php');


//ERROR if this page is loaded without first going trough the previous pages: redirect.
if(!isset($_POST["firstname"])) {
    echo '<meta http-equiv="refresh" content="0;URL=insert_person1.php" />';
    exit;
}

/**
 * Retrieve data from POST
 */
$firstname = ucfirst($_POST["firstname"]);
$lastname = ucfirst($_POST["lastname"]);
        
$network_string = ($_POST["networks"]);
$network_array = unserialize(urldecode($network_string));

try{
/**
 * Case 1 in inserting a person in the database
 * There are no more networks to search for this person           
 */
if (count($network_array) == 0) {
    ?>

    <form name="auto_form" action="insert_person_end.php" method="post">
        <input type="hidden" value="<?php echo $firstname ?>" name="firstname" />
        <input type="hidden" value="<?php echo $lastname ?>" name="lastname" /><a href="insert_person2.php" id="" title="insert_person2">insert_person2</a>
    </form>
    
    <script language = "JavaScript">
    document.auto_form.submit();
    </script>

    <?php
    
} else {
    //Getting data for the next network.
    $network_name = $network_array[0];
    $urls = call_user_func_array($network_name.'_scrape'.'::search_person',array($firstname,$lastname));

            
    $column_array = retrieve_columns($network_name.'_person',$con);
            
}
//Case when this person is already in the database.
if (already_in_database($firstname,$lastname,$con) && count($network_array) == count(retrieve_networks($con))) {
    echo "<h2> " . $firstname . " " . $lastname . " has already been inserted in the database </h2>";
    echo "You can however continue if you know a new network will be added for this person. </br>";
    ?>
    <a href = "insert_person1.php"> Go back and insert a new person </a>
    </br>
    <hr>
    
    <?php    
}  
    
if ($urls == null) {        
    ?>
    <h2>The person you're looking for has not been found in the <?php echo ucwords($network_name)?> network.</h2>
    <h3>Continue with the next network</h3>
    <form name="confirm" action="insert_person2.php" method="post">
        <input type="hidden" value="<?php echo $firstname ?>" name="firstname" />
        <input type="hidden" value="<?php echo $lastname ?>" name="lastname" />
                    
        <?php
        $network_array2 = $network_array;
        array_shift($network_array2); 
        ?>
                    
        <input type="hidden" value="<?php echo urlencode(serialize($network_array2)) ?>" name="networks" />
        <input type="submit" value="confirm">
    </form>
    <?php
} else {
    ?> 
    <img src="<?php echo ($network_name)?>_logo.png" alt="logo" height="75" width="150" style="float:left">       
    <h2><?php echo ucwords($network_name)?> network: Select the person you're looking for.</h2>
    <h3><?php echo count($network_array)?> networks to go!</h3>
    </br>
    <hr>
    <table>
        <thead>
            <th style="color:red">Select</th>
            
            <?php 
                           
            for ($i = 2; $i < count($column_array); $i++) {
                echo "<th>";
                echo ucwords(str_replace("_"," ", $column_array[$i]));
                echo "</th>";
            }
            ?>
        </thead>
       
        <tbody>
            <?php
            foreach ($urls as $url) {
                $result = call_user_func_array($network_name.'_scrape'.'::get_person',array($url));
                $name = $result["name"];
                $given_name = $firstname. " ".$lastname;
                similar_text($given_name,$name,$procent);
                if($procent > 70 || strpos($name,$given_name) !== false) {
                    echo "<tr>";
                    ?>
                    
                    <td>
                        <form name="checkbox" action="insert_person3.php" method="post">
                            <?php 
                            $resultstring = urlencode(serialize($result));
                            ?>
                            <input type="checkbox" name="persons[]" value="<?php echo $resultstring ?>" checked />
                            <input type="hidden" name="urls[]" value="<?php echo $url ?>" />
                            <input type="hidden" name="firstnames[]" value="<?php echo $firstname ?>" />
                            <input type="hidden" name="lastnames[]" value="<?php echo $lastname ?>" />
                            <input type="hidden" name="networks[]" value="<?php echo urlencode(serialize($network_array)) ?>" />
                    </td>
                        
                        <?php
                    
                    echo "<td>";
                    echo "<a href='".$url."' target='_blank'>".$name."</a>";
                    echo "</td>";
                    for ($i = 3; $i < count($column_array); $i++) {
                        echo "<td>".$result[$column_array[$i]]."</td>";                        
                    }
                
                        echo "</tr>";
                    }
                }
                echo "</tbody>";
                ?>
            </tbody>
        </table>
        <input type="submit" value="Add Selected">
        </form>
    <?php
    }
    ?>
    <h3>Supply the right URL yourself</h3>
    <form name="confirm" action="insert_person2_URLNotFound.php" method="post">
        url: <input type="url" name="url"/>
        <input type="hidden" value="<?php echo $firstname ?>" name="firstname" />
        <input type="hidden" value="<?php echo $lastname ?>" name="lastname" />
        <input type="hidden" value="<?php echo urlencode(serialize($network_array)) ?>" name="networks" />
        <input type="submit" value="confirm">
    </form>
    <?php
 } catch(Exception $e) {
     echo  "<script>";
     echo "window.alert('Something went wrong, try again later. ". $e."');";
     echo "</script>";
 }  
 ?>
    <h3>Skip this database if you don't want to insert this person in the <?php echo ucwords($network_name)?> Network.</h3>
    <form name="confirm" action="insert_person2.php" method="post">
        <input type="hidden" value="<?php echo $firstname ?>" name="firstname" />
        <input type="hidden" value="<?php echo $lastname ?>" name="lastname" />
        <?php
        array_shift($network_array); 
        ?>
        <input type="hidden" value="<?php echo urlencode(serialize($network_array)) ?>" name="networks" />
        <input type="submit" value="skip">
    </form>

    <?php
    include('/home/thesis-std/footer.php');
    ?>