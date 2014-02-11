<?php
$page_title = 'Insert Person';
        
include_once('menu.php');
include_once('scrape.php');
include_once('database.php');
$firstname =  ucfirst(mysql_real_escape_string($_POST["firstname"]));
$lastname =  ucfirst(mysql_real_escape_string($_POST["lastname"]));
        
$network_string =  mysql_real_escape_string($_POST["networks"]);
$network_array = unserialize(urldecode($network_string));
                
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
    $network_name = $network_array[0][0];
    $urls = call_user_func_array($network_name.'_scrape'.'::search_person',array($firstname,$lastname));
            
    $column_array = retrieve_columns($network_name.'_person',$con);
            
}

if (already_in_database($firstname,$lastname,$con)) {
    echo "<h3> " . $firstname . " " . $lastname . " has already been inserted in the database </h3>";
    echo "You can however continue if you know a new network will be added for this person. </br>";
?>
    <a href = "insert_person1.php"> Go back and insert a new person </a>
<?php    
}    
if ($urls == null) {        
    ?>
    <h2>The person you're looking for has not been found in the <?php echo $network_name?> network.</h2>
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
    ?>        
    <h2>Select the person you're looking for.</h2>
    <table>
        <thead>
            <?php                
            for ($i = 3; $i < count($column_array); $i++) {
                echo "<th>";
                echo ucwords(str_replace("_"," ", $column_array[$i]));
                echo "</th>";

            }
                
            ?>
            <th></th>
        </thead>
        <tbody>
            <?php
            foreach ($urls as $url) {
                $result = call_user_func_array($network_name.'_scrape'.'::get_person',array($url));
                echo "<tr>";
                for ($i = 3; $i < count($column_array); $i++) {
                    echo "<td>".$result[$column_array[$i]]."</td>";                        
                }
                    
                    
                ?>
                <td>
                    <form name="confirm" action="insert_person3.php" method="post">
                        <?php 
                        $resultstring = urlencode(serialize($result));
                        ?>
                        <input type="hidden" value="<?php echo $resultstring ?>" name="result" />
                        <input type="hidden" value="<?php echo $url ?>" name="url" />
                        <input type="hidden" value="<?php echo $firstname ?>" name="firstname" />
                        <input type="hidden" value="<?php echo $lastname ?>" name="lastname" />
                        <input type="hidden" value="<?php echo urlencode(serialize($network_array)) ?>" name="networks" />
                        <input type="submit" value="confirm">
                    </form>
                    <?php
                    echo "</td>"; 
                    echo "</tr>";
                }
            }
            ?>
            <tbody>  
            
                <?php include('footer.php')?>     