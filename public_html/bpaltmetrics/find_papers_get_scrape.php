 <?php
   // error_reporting(E_ALL);
//    ini_set('display_errors', TRUE);
//    ini_set('display_startup_errors', TRUE);
    $page_title = "Processing";
    require_once("/home/thesis-std/support/bpaltmetrics/scrape.php");
    require_once("/home/thesis-std/support/bpaltmetrics/menu.php");
    
    //ERROR if this page is loaded without first going trough the previous pages: redirect.
    if(!isset($_POST["pId"])) {
        echo '<meta http-equiv="refresh" content="0;URL=find_papers.php" />';
        exit;
    }
  
  
    $pId =   $_POST["pId"];
    $networks = unserialize(urldecode($_POST["networks"]));
    $urls = unserialize(urldecode($_POST["urls"]));
    $network = $_POST["network"];
    if(isset($_POST["papers_this"])) {
        $papers_this = unserialize(urldecode($_POST["papers_this"]));
    } else {
        $papers_this = array();
    }
    $papers = unserialize(urldecode($_POST["papers"]));
    echo "Searching papers for: ".$network;
    if(count($urls) == 0) {
        //We are done, continue to find_papers_get.php. fist put papers in database (temp in array form).        
        $merged = array();

        foreach($papers_this as $to_merge) {
            array_splice($merged,count($merged),0,$to_merge);
        }
        $papers[$network] = $merged;
        var_dump(count($papers));
        ?>
        <form name="next_form" action="find_papers_get.php" method="post">
            <input type="hidden" value="<?php echo $pId?>" name ="pId" />
            <input type="hidden" value="<?php echo urlencode(serialize($networks))?>" name="networks" />
            <input type="hidden" value="<?php echo urlencode(serialize($papers))?>" name="papers" />
            
        <!-- <input type="submit" value="submit"/> -->
                          
        </form>
        <script language = "JavaScript">
        document.next_form.submit();
        </script> 
        <?php
    } else {
        //We are not done and get the papers for the next network.
        
        //first url:
        $url = array_shift($urls);
        $url_papers = call_user_func_array($network.'_scrape'.'::search_papers',array($url));
          
        $papers_this[] = $url_papers;
        ?>
        <form name="next_form" action="find_papers_get_scrape.php" method="post">
            <input type="hidden" value="<?php echo $pId?>" name ="pId" />
            <input type="hidden" value="<?php echo $network?>" name ="network" />
            <input type="hidden" value="<?php echo urlencode(serialize($networks))?>" name="networks" />
            <input type="hidden" value="<?php echo urlencode(serialize($papers_this))?>" name="papers_this" />
            <input type="hidden" value="<?php echo urlencode(serialize($papers))?>" name="papers" />
            
            <input type="hidden" value="<?php echo urlencode(serialize($urls))?>" name="urls" />
        <!-- <input type="submit" value="submit"/> -->
        </form>
        <script language = "JavaScript">
        document.next_form.submit();
        </script> 
        <?php
          
          
    }
        



    ?>