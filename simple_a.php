<?php 

$to_crawl = "https://homegate.ch/mieten/immobilien/kanton-zuerich/trefferliste?ep=1";

function get_links($url){
    $domain_only =  parse_url($url);
    $domain_only = $domain_only['host'];
    $input = @file_get_contents($url);
    $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
    preg_match_all("/$regexp/siU",$input,$matches);
    $l = $matches[2];
    //     Get only the links that have an ID

    $urlParts = explode('/', str_ireplace(array('http://', 'https://'), '', $url));
    foreach($l as $link){
        if (strpos($link, '/'.$urlParts['1'].'/') !== false  ) {
            $get_id = str_replace('/'.$urlParts['1'].'/','',$link);
            if(filter_var($get_id, FILTER_VALIDATE_INT) ){
                echo $domain_only.$link.'<br/>';
            }
        }
    }

}

// Call function
get_links($to_crawl);
