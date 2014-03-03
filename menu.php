<!DOCTYPE HTML>
<html>
    <head>
        <link type = "text/css" rel = "stylesheet" href= "menu.css">
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
        <title><?php echo $page_title ?></title>
    </head>
    <body>        
        <ul id="nav">
            <li><a href="insert_person1.php">Insert Person</a></li>
            <li><a href="visualisation_db_select_parameters.php">visualise network</a></li>
            <li><a href="find_papers.php">Search papers</a></li>
            <li><a href="#">Item 4</a></li>
        </ul>
        
        <?php
        set_error_handler("warning_handler", E_WARNING);

        function warning_handler($errno, $errstr) { 
        }
        
        
        
        ?>
