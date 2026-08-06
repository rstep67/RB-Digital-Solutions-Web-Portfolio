<?php

/**Wordpress controller for homepage

structure follows approach demonstrated in:
Kumar, A. (2024) 'WordPress REST API Get All Posts Tutorial'
Available at: https://ashvanikumar.com/wordpress-rest-api-get-all-posts-tutorial/ 

adapted from echo-based script to function that returns data to fit controller/view seperation in project
added caching seperate from tutorial to stop API being called every time homepage loads and slowing it down
*/

function getLatestWordpressPosts() {
$website_url = 'https://willdaywm.co.uk';
$api_endpoint = '/wp-json/wp/v2/posts';

$cache_dir = __DIR__ . '/../../cache';
$cache_file = $cache_dir . '/wordpress_posts.json';
$cache_lifetime = 3600;

//if cache ecists and hasnt expired serve from there
if (file_exists($cache_file) && (time()-filemtime($cache_file)) < $cache_lifetime) {
    $wp_posts = json_decode(file_get_contents($cache_file));

    if(is_array($wp_posts)) {
        return $wp_posts;
    }
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $website_url . $api_endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    error_log('wordpress api cURL error: ' . curl_error($ch));

/*request failed fallback to stale cache*/    
if (file_exists($cache_file)) {
    $posts = json_decode(file_get_contents($cache_file));
    return is_array($posts) ? $posts : [];
}

return[];

}

$posts = json_decode($response);

/*failsafe for invalid post data*/
if (!is_array($posts)) {
    error_log('wordpress api returned unexpected data');
    return[];
}


/*cache response for next time and create folder if it doesnt exist*/
if (!is_dir($cache_dir)) {
    mkdir($cache_dir,0777, true);
}
file_put_contents($cache_file,$response);
return $posts;

}

$latest_posts = getLatestWordpressPosts();