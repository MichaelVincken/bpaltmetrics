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
        preg_match("/Author\/(.*)\//", $url, $matches);
        $id = $matches[1];
        //new url for documents of this person
        $url = "http://academic.research.microsoft.com/Detail?entitytype=2&searchtype=2&id=".$id;
        //get first page
        $html = file_get_html($url);
        //find navigation for next pages.
        $ret = $html->find('.page-navigator a');
        $array = array();
        //array[0] = html of first page
        $array[0] = $html;
        //find html for every other page.
        for($i = 1; $i < count($ret); $i ++) {
            $ur = html_entity_decode("http://academic.research.microsoft.com".$ret[$i-1]->href);
            $html = file_get_html($ur);
            $array[$i] = $html;
        }
        //article_array: array with all articles.
        $article_array = array();
        //for each page: get all articles.
        foreach($array as $html) {
            $ret = $html->find('h3 a');
            $i = 0;
            while($i < count($ret)) {
                $element = array();
                $element["url"] = html_entity_decode("http://academic.research.microsoft.com/".$ret[$i]->href);
                $element["title"] = html_entity_decode($ret[$i] ->plaintext);
                //special case when citations are included.
                    if(isset($ret[$i+1]) && preg_match("/Citations:\s(.*)/",$ret[$i+1]->plaintext,$matches)==1) {
                        $element["citations"] = intval($matches[1]);
                        $i = $i+2;
                    } else {
                        $element["citations"] = 0;
                        $i++;
                    } 
                $element["network"] = "microsoft";
                    
                array_push($article_array,$element);
            }
        }
        
        return $article_array;       
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
        $array["affiliation"] = html_entity_decode($ret[0]->plaintext);
        
        // Terugvinden study_field
        $ret = $html->find("span[id=cit-int-read]");
        $array["study_field"] = html_entity_decode($ret[0]->plaintext);
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
    
    public static function search_papers($url) {
        include_once("simple_html_dom.php");
        preg_match("/user=(.*)/", $url, $matches);
        $id = $matches[1];
        //new url for documents of this person
        $url = "http://scholar.google.com/citations?hl=en&user=".$id."&view_op=list_works&pagesize=100";
        $article_array = array();
        do {
            $html = file_get_html($url);
            $ret = $html->find('.cit-table .item');
            // each $r is a row in the table. extract title, url and citations.
            foreach($ret as $r) {
                $array = array();
                $array['title'] = html_entity_decode($r->ChildNodes(0)->children(0)->plaintext);
                $citations = $r->childNodes(1)->plaintext;
                if($citations == '') $citations = 0;
                $array['citations'] = intval($citations);
                $array['url'] = "http://scholar.google.com/".html_entity_decode($r->childNodes(0)->children(0)->href);
                $array["network"] = "google";
                array_push($article_array,$array);
            }
            $url_changed=FALSE;
            $ret = $html->find('.g-section #citationsForm .cit-dark-link');
            foreach($ret as $r) {
                if(strstr(($r->plaintext),"Next") !== FALSE) {
                    
                    $url = "http://scholar.google.com/".html_entity_decode($r->href);
                    $url_changed=TRUE;
                    break;
                }
                    
            }
            
        } while($url_changed);
        return $article_array;
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
        $array["affiliation"] = html_entity_decode($ret[3]->plaintext);
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
            $url_part = 
            $url_part = html_entity_decode($ret[$i]->href);
            preg_match("/aid=(.*)/", $url_part, $matches);
            $id = $matches[1];
            $url = "http://citeseerx.ist.psu.edu/viewauth/summary?aid=".$id;
            array_push($array,$url);
            
            
        }
        
        return $array;
        
    }
    
    public static function search_papers($url) {
        include_once("simple_html_dom.php");
        $url =  "http://citeseerx.ist.psu.edu/viewauth/summary?aid=62171";
        $url = $url . "&list=full";
        $html = file_get_html($url);
        $ret = $html->find('#viewContent #viewContent-inner .refs tr');
        $result_array = array();
        for ($i = 1;$i < count($ret); $i++) {
            $array = array();
            $array["citations"] = intval($ret[$i]->children(0)->plaintext);
            $array["title"] = html_entity_decode($ret[$i]->children(1)->children(0)->plaintext);
            $array["url"] = "http://citeseerx.ist.psu.edu".html_entity_decode($ret[$i]->children(1)->children(0)->href);
            $array["network"] = "citeseer";
            array_push($result_array,$array);
        }
        return $result_array;
        
    }
}

class acm_scrape {
    public static function get_person($url) {
        include_once("simple_html_dom.php");
        $html = file_get_html($url);
        //Affiliation
        $ret = $html->find('td.small-text div[align] a');
        $result_array = array();
        $affiliation_array = array();
        foreach($ret as $r) {
            array_push($affiliation_array,html_entity_decode($r->plaintext));
        }
        $result_array["affiliation"] = implode(" and ",$affiliation_array);
        //Name
        $ret = $html->find('td[style] span.small-text strong');
        $result_array["name"] = html_entity_decode($ret[0]->plaintext);
        //metrics:
        $ret = $html->find('table tbody tr td table tbody tr[valign] td table tbody tr td.small-text table tbody tr td.small-text');
        $result_array["publications"] = intval(str_replace(',','',$ret[3]->plaintext));
        $result_array["citations"] = intval(str_replace(',','',$ret[5]->plaintext));
        $result_array["downloads"] = intval(str_replace(',','',$ret[13]->plaintext));
        
        return $result_array;
    }
    
    public static function search_person($name, $lastname) {
        include_once("simple_html_dom.php");
        $lastname = explode(' ',$lastname);
    	$search_string = implode('+',$lastname);
        $url = "http://dl.acm.org/results.cfm?adv=1&COLL=DL&qrycnt=2215510&DL=ACM&Go.x=44&Go.y=12&peoplezone=Author&people=".$name."+".$search_string."&peoplehow=and";
        $html = file_get_html($url);
        $ret = $html->find('div.authors a');
        $url_array = array();
        foreach($ret as $r) {
            $url = html_entity_decode($r->href);
            preg_match("/id=(\d*)&/", $url, $matches);
            $id = $matches[1];
            $url = "http://dl.acm.org/author_page.cfm?id=".$id;
            array_push($url_array,$url);
        }
        $count = array_count_values($url_array);
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
        $url = $url."&perpage=all";
        $html = file_get_html($url);
        $ret = $html->find('body div table tbody tr td table tbody tr td table tbody tr td table tbody tr td table tbody tr td[colspan] a.medium-text');
        $intermediate_result_array = array();
        foreach($ret as $r) {
             $title = html_entity_decode($r->plaintext);
             $url = html_entity_decode($r->href);
             preg_match("/id=((\d|\.)*)&/", $url, $matches);
             $id = $matches[1];
             $url = "http://dl.acm.org/citation.cfm?id=".$id;
             $array = array();
             $array["title"] = $title;
             $array["url"] = $url;
             array_push($intermediate_result_array,$array);
        }
        $ret = $html->find('body div table tbody tr td table tbody tr td table tbody tr td table tbody tr td table tbody tr td table tbody tr td.small-text');
        $result_array = array();
        for ($i = 0;$i < count($ret); $i++) {
            $r = $ret[$i];
            $string =  html_entity_decode($r->plaintext,ENT_HTML5,"ISO-8859-1");
            $array = $intermediate_result_array[$i];
            preg_match("/Downloads \(12 Months\): (.*),/", $string, $matches);
            $array["downloads"] = intval(str_replace(',','',$matches[1]));
            preg_match("/Citation Count: (.*)/", $string, $matches);
            $array["citations"] = intval(str_replace(',','',$matches[1]));;
            $array["network"] = "acm";
            array_push($result_array,$array);
        }
        return $result_array;
    }
}

// Google Scholar
//var_dump(Google_scrape::get_person("http://scholar.google.com/citations?user=PZURMD0AAAAJ"));
//var_dump(Google_scrape::search_person("Erik","Duval"));
//var_dump(Google_scrape::search_papers("http://scholar.google.com/citations?user=PZURMD0AAAAJ"));
// CiteSeerX
//var_dump(Citeseer_scrape::get_person("http://citeseerx.ist.psu.edu/viewauth/summary?aid=62171&list=full&list=full"));
//var_dump(Citeseer_scrape::search_person("Erik","Duval"));
//var_dump(Citeseer_scrape::search_papers("/"));


//What to do with: f.e. Matthijs van Leeuwen
//http://scholar.google.com/citations?hl=en&view_op=search_authors&mauthors=matthijs+van+leeuwen
//var_dump(google_scrape::search_person("matthijs","van leeuwen"));

//Microsoft
//var_dump(microsoft_scrape::search_papers("http://academic.research.microsoft.com/Author/1406490/erik-duval?query=erik%20duval"));

//ACM
//var_dump(acm_scrape::get_person(""));
//var_dump(acm_scrape::search_person("ERik","duval"));
//var_dump(acm_scrape::search_papers("http://dl.acm.org/author_page.cfm?id=81100325487"));
?>

