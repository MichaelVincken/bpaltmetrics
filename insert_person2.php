<?php
$page_title = 'Insert Person';
        
include_once('menu.php');
include_once('scrape.php');
include_once('database.php');

// Retrieve data from POST
$firstname =  ucfirst(mysql_real_escape_string($_POST["firstname"]));
$lastname =  ucfirst(mysql_real_escape_string($_POST["lastname"]));
$network_string =  mysql_real_escape_string($_POST["networks"]);
$network_array = unserialize(urldecode($network_string));

// -- CASE 0 :: There are no more networks --              
if (count($network_array) == 0) {
    ?>
    <form name="auto_form" action="insert_person_end.php" method="post">
        <input type="hidden" value="<?php echo $firstname ?>" name="firstname" />
        <input type="hidden" value="<?php echo $lastname ?>" name="lastname" />
    </form>
    <script language = "JavaScript">
    document.auto_form.submit();
    </script>            
    <?php
} else {
    
    // Retrieve data for the next network.
    $network_name = $network_array[0];
    $urls = call_user_func_array($network_name.'_scrape'.'::search_person',array($firstname,$lastname));
            
    $column_array = retrieve_columns($network_name.'_person',$con);        
}

// -- CASE 1 :: This person is already into the database --
if (already_in_database($firstname,$lastname,$con) && count($network_array) == count(retrieve_networks($con))) {
    echo "<h2> " . $firstname . " " . $lastname . " has already been inserted in the database </h2>";
    echo "You can however continue if you know a new network will be added for this person. </br>";
    ?>
    <a href = "insert_person1.php"> Go back and insert a new person </a>
    <?php
}  

// -- CASE 2 :: This person has not been found in this network --
if ($urls == null) {        
    ?>
    <h2>The person you're looking for has not been found in the <?php echo ucwords($network_name)?> network.</h2>
    <h3>Supply the right URL yourself</h3>
    <form name="confirm" action="insert_person2_URLNotFound.php" method="post">
        url: <input type="url" name="url"/>
        <input type="hidden" value="<?php echo $firstname ?>" name="firstname" />
        <input type="hidden" value="<?php echo $lastname ?>" name="lastname" />
        <input type="hidden" value="<?php echo urlencode(serialize($network_array)) ?>" name="networks" />
        <input type="submit" value="confirm">
    </form>
    <h3>Continue with the next network</h3>
    <form name="confirm" action="insert_person2.php" method="post">
        <input type="hidden" value="<?php echo $firstname ?>" name="firstname" />
        <input type="hidden" value="<?php echo $lastname ?>" name="lastname" />
                    
        <?php
        array_shift($network_array); 
        ?>
                    
        <input type="hidden" value="<?php echo urlencode(serialize($network_array)) ?>" name="networks" />
        <input type="submit" value="confirm">
    </form>
    <?php
} else {
    
    // -- CASE 3 :: Selecting the person to be inserted in the database --
    ?>
    <h2><?php echo ucwords($network_name)?> Network: Select the person you're looking for.</h2>
    
    <script language = "JavaScript">  
    
    function onSelect() {  
    if(confirm('Are you sure you want to select?') == true) {  
        return true;  
    }   else {  
        return false;  
        }  
    }  
    
    function ClickCheckAll(vol) {  
    var i=1;  
        for(i=1;i<=document.form_checkbox.hdnCount.value;i++) {  
            if(vol.checked == true) {
                eval("document.form_checkbox.chkSel"+i+".checked=true");  
            } else {  
                eval("document.form_checkbox.chkSel"+i+".checked=false");  
            }  
        }  
    }
    </script>
    
    <form name="form_checkbox" action="checkbox.php" method="post" OnSubmit="return onSelect();"> 
    <table>
        <thead>
            <?php                
            for ($i = 2; $i < count($column_array); $i++) {
                echo "<th>";
                echo ucwords(str_replace("_"," ", $column_array[$i]));
                echo "</th>";
            }                
            ?>
            <th width="30"><div align="center"><input name="CheckAll" type="checkbox" id="CheckAll" value="Y" onClick="ClickCheckAll(this);"></div></th>
        </thead>
        <tbody>
            <?php
            foreach ($urls as $url) {
                $result = call_user_func_array($network_name.'_scrape'.'::get_person',array($url));
                $name = $result["name"];
                $given_name = $firstname. " ".$lastname;
                similar_text($given_name,$name,$procent);
                if($procent > 50) {
                    echo "<tr>";
                    echo "<td>";
                    echo "<a href=".$url.">".$name."</a>";
                    echo "</td>";
                    for ($i = 3; $i < count($column_array); $i++) {
                        echo "<td>".$result[$column_array[$i]]."</td>";                        
                    } 
                    ?>
                    <td align="center"><input type="checkbox" name="chkSel[]" id="chkSel<?=$i;?>" value="<?=$given_name;?>"></td>
                        <?php
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
        
        <input type="submit" name="btnSelect" value="Select">  
        <input type="hidden" name="hdnCount" value="<?=$i;?>">
        
        </form> 
            <?php
        } ?>
        <h3>Or skip this database if you don't want to insert this person in the <?php echo ucwords($network_name)?> Network.</h3>
        <form name="confirm" action="insert_person2.php" method="post">
            <input type="hidden" value="<?php echo $firstname ?>" name="firstname" />
            <input type="hidden" value="<?php echo $lastname ?>" name="lastname" />
    
            <?php
            array_shift($network_array); 
            ?>
    
            <input type="hidden" value="<?php echo urlencode(serialize($network_array)) ?>" name="networks" />
            <input type="submit" value="confirm">
        </form>
        <?php
            include('footer.php');
            ?>