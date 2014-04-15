<?php
ini_set('display_errors', 1);
$page_title = "compare people";
require('/home/thesis-std/support/bpaltmetrics/database.php');
require('/home/thesis-std/support/bpaltmetrics/menu.php');
$persons = retrieve_persons($con);
$mouseOverString = "Select the people you want to compare.";
include('tooltip.php');
 
 ?>
    <table>
        <thead>
            <th style="color:red">Select</th>
            <th>First Name</th>
            <th>Last Name</th>
        </thead>
        <tbody>
            <form name="checkbox" action="visualise_db_compare_select_parameter.php" method="post">
            
            <?php
            foreach($persons as $person ) {
                ?>
                <tr>
                    <td>
                            <input type="checkbox" name="persons[]" value="<?php echo $person["pId"]; ?>" />
                    </td>
                    <td>
                       <?php echo $person["name"]; ?> 
                   </td>
                   <td>
                       <?php echo $person["lastName"]; ?>
                   </td>
                </tr>        
            <?php         
                
            }
            ?>
                    <input type="submit" style="font-size: larger; color: red;" value="Add Selected">
                    </form>
        </tbody>
<?php
include('/home/thesis-std/support/bpaltmetrics/footer.php');
?>