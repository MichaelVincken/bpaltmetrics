<?php
$page_title = 'Checkbox Person';
?>

<html>  
<head>  
    <title>Selecting the person you want.</title>  
</head>  
<body>  
    <?  
    for($i=0;$i<count($_POST["chkSel"]);$i++) {  
        if($_POST["chkSel"][$i] != "") {  
            ?>
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
        }  
    }  
  
    echo ":(";  
    ?>  
</body>  
</html>