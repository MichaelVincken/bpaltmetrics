<?php
// scraping basis
// include the library
// include('simple_html_dom.php');

class microsoft_scrape {
    
    public static function get_person($url) {
   		include_once("PQLite.php");
        // Retrieve html
        $pq = new PQLite(file_get_contents($url));
        // Retrieve all meta
        $arr = $pq->find('meta');
        // Get description
        $description = $arr->get(2)->getAttr("content");
        // Extract Name, Values and Field of Study
        
        // First: Name
        preg_match("/View\s(.*)\'s/", $description, $matches);
        $name = $matches[1];
        
        // Second: Values
        preg_match_all("/\d+/", $description, $matches);
        $publications = $matches[0][0];
        $citations = $matches[0][1];
        $field_rating = $matches[0][2];
        
        // Third: Field of Study
        preg_match("/study:\s(.*)/", $description, $matches);
        $study_field = $matches[1];
        return array("name" => $name, "publications" => $publications, "citations" => $citations, "field_rating" => $field_rating, "study_field" => $study_field);
    }
    
    //returns the top two url's!
    public static function search_person($name,$lastname) {
    	include_once("PQLite.php");
    	$lastname = explode(' ',$lastname);
    	$search_string = implode('%20',$lastname);
        $url = "http://academic.research.microsoft.com/Search?query=".$name."%20".$search_string."&s=0";
        $pq = new PQLite(file_get_contents($url));
        //array of html-tags
        $arr = $pq->find(".author-name-tooltip");
        //$array is an array with urls
        $array = array();
        for($i=0;$i < $arr->getNumTags(); $i++) {
            array_push($array,$arr->get($i)->getAttr("href"));
        }
        //$count: array with url as key and number as value
        $count = array_count_values($array);
        arsort($count);
        //select highest two.
        $highest_two = array_slice($count,0,2);
        //return array: get keys from highest_two
        $return_array = array();
        foreach($highest_two as $key=>$value) {
            array_push($return_array,$key);
        }
        return $return_array;
    }
    
    public static function search_papers($url) {
        include_once("simple_html_dom.php");
        //include_once("PQLite.php");
        $url = "http://academic.research.microsoft.com/Author/1406490/erik-duval?query=erik%20duval";
        preg_match("/Author\/(.*)\//", $url, $matches);
        $id = $matches[1];
        //new url for documents of this person
        $url = "http://academic.research.microsoft.com/Detail?entitytype=2&searchtype=2&id=".$id;
       
       
        $html = file_get_html("ErikDuval.html");
        
        $ret = $html->find('.page-navigator a');
        $array = array();
        $array[0] = $html;
        for($i = 1; $i < count($ret); $i ++) {
            $ur = html_entity_decode("http://academic.research.microsoft.com".$ret[$i-1]->href);
            $html = file_get_html($ur);
            $array[$i] = $html;
        }
        
        $article_array = array();
        foreach($array as $html) {
            echo "finding </br>";
            $ret = $html->find('h3 a');
            echo "found ".count($ret);
            $i = 1;
            while($i < count($ret)) {
                $element = array();
                $element["url"] = $ret[$i]->href;
                $element["title"] = $ret[$i] ->plaintext;
                if(preg_match("/citations:\s(.*)/",$ret[$i+1]->plaintext,$matches)==TRUE) {
                    $element["citations"] = $matches[1];
                    $i = $i+2;
                } else {
                    $element["citations"] = 0;
                    $i++;
                }
               
                
                
                array_push($article_array,$element);
            
        }
        
        return $article_array;
     }   
    }
    
}

class google_scrape {
    
    public static function get_person($url) {
        include_once("simple_html_dom.php");
        
        $html = file_get_html($url);        
        $array = array();
        $ret = $html->find('#cit-name-display');
        $array["name"] = ($ret[0]->plaintext);
        
        $ret = $html->find('td[class=cit-borderleft cit-data]');
        $array["citations"] = ($ret[0]->plaintext);
        $array["h-index"] = ($ret[2]->plaintext);
        $array["i10-index"] = ($ret[4]->plaintext);
     
        $ret = $html->find('#cit-affiliation-read .cit-in-place-nohover');
        $array["affiliation"] = ($ret[0]->plaintext);
        
        // Terugvinden study_field
        $ret = $html->find("span[id=cit-int-read]");
        $array["study_field"] = ($ret[0]->plaintext);
        return $array;
    }
    
    public static function search_person($name, $lastname) {
        include_once("simple_html_dom.php");
        
        $lastname = explode(' ',$lastname);
    	$search_string = implode('+',$lastname);
        $url = "http://scholar.google.com/citations?hl=en&view_op=search_authors&mauthors=" . $name . "+" . $search_string;

        $html = file_get_html($url);
        $ret = $html->find('a[class=cit-dark-large-link]');
        $array = array();
        
        foreach($ret as $r) {
            $urlref = "http://scholar.google.com" . ($r->href);
            array_push($array, $urlref);
        }
        
        return $array;
    }
}

class citeseer_scrape {
    
    public static function get_person($url) {
        include_once("simple_html_dom.php");
        $url = $url . "&list=full";
        
        //Retrieve html
        $html = file_get_html($url);
        $array = array();
        
        $ret = $html->find('h2');
        $string_array = explode(" ",$ret[0]->plaintext);
        $string = implode(" ",array_slice($string_array,0,-1));
        $array["name"] = $string;
        
        // Retrieve all relevant td
        $ret = $html->find('#authInfo tbody tr td');
        
        // Get head info
        $array["affiliation"] = $ret[3]->plaintext;
        $array["publications"] = $ret[5]->plaintext;
        $array["h-index"] = $ret[7]->plaintext;
       
        // Citations is more difficult, we have to calculate them ourselves.
        $ret = $html->find('#viewContent #viewContent-inner .refs .title');
        $cit = 0;
        for ($i = 1;$i < count($ret); $i++) {
            $cit = $cit + $ret[$i]->plaintext;
        }
        
        $array["citations"] = $cit;
        return $array;     
    }
    
    public static function search_person($name, $lastname) {
        include_once("simple_html_dom.php");
        $lastname = explode(' ',$lastname);
    	$search_string = implode('+',$lastname);

        $url = "http://citeseerx.ist.psu.edu/search?q=" . $name . "+" . $search_string . "&submit=Search&uauth=1&sort=ndocs&t=auth";
        $html = file_get_html($url);
        $ret = $html->find('.result a[href^=/view]');
        $array = array();
        for($i = 0; $i < count($ret); $i++) {
            array_push($array,"http://citeseerx.ist.psu.edu" . ($ret[$i]->href));
        }
        
        return $array;
        
    }
}

// Google Scholar
//var_dump(Google_scrape::get_person("http://scholar.google.com/citations?user=PZURMD0AAAAJ"));
//var_dump(Google_scrape::search_person("Erik","Duval"));

// CiteSeerX
//var_dump(Citeseer_scrape::get_person("http://citeseerx.ist.psu.edu/viewauth/summary?aid=62171&list=full&list=full"));
//var_dump(Citeseer_scrape::search_person("Erik","Duval"));



//What to do with: f.e. Matthijs van Leeuwen
//http://scholar.google.com/citations?hl=en&view_op=search_authors&mauthors=matthijs+van+leeuwen
//var_dump(google_scrape::search_person("matthijs","van leeuwen"));

//Microsoft
var_dump(microsoft_scrape::search_papers("/"));

?>

