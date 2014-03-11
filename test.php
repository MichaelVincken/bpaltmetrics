<?php
require('database.php');
parallel($con,"citations",TRUE);
?>