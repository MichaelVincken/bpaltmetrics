<?php
$page_title = "visualise papers";
require('menu.php');
require('database.php');

$pId = ($_POST["pId"]);
//retrieve networks relevant for this user.
$networks = retrieve_networks_person($pId,$con);
//Now calculate each set.

//First calculate which networks each set has to have.
$set_array = array();
//Loop through each possible combination   
for ($i = 0; $i < pow(2, count($networks)); $i++) {   
    //For each combination check if each bit is set  
    $array = array();
    for ($j = 0; $j < count($networks); $j++) {  
       //Is bit $j set in $i?  
        if (pow(2, $j) & $i) {
            array_push($array,$networks[$j]);  
            }     
    }  
   array_push($set_array,$array);  
}
//Calculate the include networks and the not-include networks.
$set_array = array_slice($set_array,1);
$new_set_array = array();
foreach($set_array as $set) {
    $array = array();
    $array[0]=$set;
    $array[1]=array_diff($networks,$set);
    array_push($new_set_array,$array);
}
//Get actual records.
$set_records = array();
foreach($new_set_array as $set) {
    $set_string = implode(' ∩ ',$set[0]);
    $set_records[$set_string] = getPapersFromNetworks($set[0],$set[1],$pId,$con);
}
//Venn diagram?
include('ven_diagram.php');
//Printing to table

?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<?php
$i = 0;
foreach($set_records as $title=>$data) {
    $table = "table_".$i;
    ?>
    <table style = "width: 95%">
        <caption class = "<?php echo $table ?>">
            <div id="<?php echo $table."_arrow" ?>" class="nav-arrow">▼</div>
            <?php echo $title ?>
        </caption>
        <thead>
        </thead>
        <tbody class = <?php echo $table;?>>
            <?php
            foreach($data as $record) {
                echo "<tr>";
                echo '<td>'.$record["title"]."</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <script>
    $("caption.<?php echo $table;?>").click(function(){
      $("tbody.<?php echo $table;?>").toggle("slow","linear",changeArrow('<?php echo $table."_arrow";?>'));
    });
    
    function changeArrow(section_id) {        
        if ($('#' +section_id).text() ==  "▼"){
            $('#' +section_id).text("►");
        }
        else if ($('#' +section_id).text() ==  "►"){
            $('#' +section_id).text("▼");
        }   
    }
    
    </script>
    
    <?php
    $i++;
}

?>


<?php
require('footer.php');
?>
