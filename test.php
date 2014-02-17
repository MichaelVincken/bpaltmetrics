
<?php
$citations = "citations: 14";
preg_match("/citations:\s(.*)/", $citations, $matches);
var_dump($matches);
$element["citations"] = $matches[1];
?>