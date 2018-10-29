<?php

$to_crawl = "https://homegate.ch/mieten/immobilien/kanton-zuerich/trefferliste?ep=1";


function get_pages_links($url)
{
    $last_page = 1;
    $urlpart = parse_url($url);
    $domain_only = $urlpart['host'];
    parse_str($urlpart['query'], $ep);
    $input = @file_get_contents($url);
    $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
    preg_match_all("/$regexp/siU", $input, $matches);
    $l = $matches[2];


    //     Get only the links that have an ID
    echo '--------------- Page Number ' . $ep['ep'] . ' ---------------<br/>';
    $urlParts = explode('/', str_ireplace(array('http://', 'https://'), '', $url));
    foreach ($l as $link) {

        if (strpos($link, '?ep=') !== false) {
//            echo $link . '<br/>';
            $last_page = $link;
        }

        if (strpos($link, '/' . $urlParts['1'] . '/') !== false) {
            $get_id = str_replace('/' . $urlParts['1'] . '/', '', $link);
            if (filter_var($get_id, FILTER_VALIDATE_INT)) {
                echo $domain_only . $link . '<br/>';
            }
        }

    }
    echo '<br/>';

    $page_no = parse_url($last_page);
    parse_str($page_no['query'], $page_no);
    $last_page = $page_no['ep'];

    if ($last_page > $ep['ep']) {
        $to_crawl = str_replace('ep=' . $ep['ep'], 'ep=' . $last_page, $url);
        get_pages_links($to_crawl, $last_page);
    }


}

// Call function
get_pages_links(get_pages_links);
