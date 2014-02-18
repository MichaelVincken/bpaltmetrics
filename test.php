
<?php
include('simple_html_dom.php');
$url = "http://scholar.google.be/citations?hl=en&user=PZURMD0AAAAJ&pagesize=100&view_op=list_works&cstart=100";
$html = file_get_html($url);
$ret = $html->find('#citationsForm .cit-dark-link [plaintext^=next]');
var_dump($ret[0]->plaintext);
    ?>