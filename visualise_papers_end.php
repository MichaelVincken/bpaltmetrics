<?php

$page_title = "visualise papers";
require('menu.php');
require('database.php');

//Help mouseover:
$mouseOverString = "This page displays the papers of 1 individual. The distribution of the papers accross different networks has been calculated for you. The data displays papers that are only found in the network(s) that are specified. A network that is empty can contain several papers, but only papers another network also contains.";
include('tooltip.php');
$pId = ($_POST["pId"]);
//retrieve networks relevant for this user.
$networks = $_POST["networks"];
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
sort($new_set_array);
//Get actual records.
$set_records = array();
foreach($new_set_array as $set) {
    $set_string = implode(' ∩ ',$set[0]);
    $set_records[$set_string] = getPapersFromNetworks($set[0],$set[1],$pId,$con);
}
//Venn diagram?
$venn_nr_sets = count($networks);
$sets = array();

for($i = 0;$i<$venn_nr_sets;$i++) {
    $set_name = array_keys($set_records)[$i];
    //Calculate how many papers in total in the network.
    $numberOfPapers = count(getPapersFromNetworks([$set_name],array(),$pId,$con));
    array_push($sets,'{label: "'.$set_name.'" ,size: '.$numberOfPapers.'}');
}
$sets = implode(', ',$sets);
$sets = '['.$sets.']';


//Calculate sets with numbers.
$elements = range(0,count($networks)-1);
$array_numbers = array();
//Loop through each possible combination   
for ($i = 0; $i < pow(2, count($elements)); $i++) {   
    //For each combination check if each bit is set  
    $array = array();
    for ($j = 0; $j < count($elements); $j++) {  
       //Is bit $j set in $i?  
        if (pow(2, $j) & $i) {
            array_push($array,$elements[$j]);  
            }     
    }  
   array_push($array_numbers,$array);  
}
sort($array_numbers);
//Keep only those relevant for the overlapping.
$array_numbers = array_slice($array_numbers,1);
$overlaps = array();
for($i = $venn_nr_sets; $i < count($array_numbers); $i++) {
    $set_name = implode(",",$array_numbers[$i]);
    array_push($overlaps,'{sets: ['.$set_name.'],size: '.count($set_records[array_keys($set_records)[$i]]).'}');
}
$overlaps = '['.implode(', ',$overlaps).']';

//Printing the image
include('venn_diagram.php');
if(count($networks) > 3) {
    echo '<strong style="color:red;">Beware! visualisation of more than three networks is not always as accurate as one would like.</strong>';
}

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
            <?php
                echo "<div class='tooltip'>".$title."</div>";
                ?>
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
