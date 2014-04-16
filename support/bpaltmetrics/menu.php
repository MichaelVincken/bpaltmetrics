<?php
 ini_set('display_errors', 1);
     
?>
<!DOCTYPE HTML>
<html>
    <head>
        <link type = "text/css" rel = "stylesheet" href= "menu.css">
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
        <title><?php echo $page_title ?></title>
    </head>
    <body>        
        <ul id="nav">
            <li><a href="index.php">HOME</a></li>
            <li><a href="insert_person1.php">Insert Person</a></li>
            <li><a href="visualisation_db_select_parameters.php">Visualise Network</a></li>
            <li><a href="visualise_db_compare_select_persons.php">Compare People</a></li>
            <li><a href="visualise_papers_select_parameter.php">Visualise papers</a></li>
        </ul>
        <div id="question">?</div>
        
        <?php
        set_error_handler("warning_handler", E_WARNING);

        function warning_handler($errno, $errstr) { 
        }
        
        
        
        ?>
