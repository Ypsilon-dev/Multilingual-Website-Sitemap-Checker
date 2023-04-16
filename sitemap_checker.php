<?php
// Set the Domain & TLD
$base_url = 'https://domain.tld';


// Set the URLs of the sitemap for each language
$sitemap_urls = array(
    $base_url . '/sitemap_index.xml',
    $base_url . '/en/sitemap_index.xml',
    $base_url . '/ar/sitemap_index.xml'
);

// Function to recursively get all URLs from a sitemap
function get_sitemap_urls($url, &$urls) {
    $xml = file_get_contents($url);
    preg_match_all('/<loc>(.*?)<\/loc>/', $xml, $matches);
    foreach ($matches[1] as $match) {
        if (strpos($match, '.xml') !== false) {
            // If the URL is another sitemap, recursively get URLs from that sitemap
            get_sitemap_urls($match, $urls);
        } else {
            // Otherwise, add the URL to the array
            $urls[] = $match;
        }
    }
}

// Array to store all URLs from all sitemaps
$urls = array();

// Loop through each sitemap URL and get all URLs from it
foreach ($sitemap_urls as $url) {
    get_sitemap_urls($url, $urls);
}

function remove_url_components($urls) {
    foreach ($urls as &$url) {
        $url = str_replace(array('/en/', '/ar/'), '/', $url);
        $url_parts = parse_url($url);
        if (isset($url_parts['host'])) {
            $domain_parts = explode('.', $url_parts['host']);
            $tld = end($domain_parts);
            if (strlen($tld) <= 4) { // assume TLDs are no longer than 4 characters
                $url = str_replace($url_parts['scheme'] . '://' . $url_parts['host'] . '/', '', $url);
            }
        }
    }
    return $urls;
}


$direct_urls = remove_url_components($urls);
$counted_array = array_count_values($direct_urls);
$non_duplicates = array_keys(array_filter($counted_array, function($value) { return $value == 1; }));

$new_values = array(
    $base_url . '/',
    $base_url . '/ar/',
    $base_url . '/en/'
);

$new_array = array();

// Loop through each element of the original array
foreach ($non_duplicates as $element) {
    // Concatenate the three new values with the original array element
    $new_element_1 = $new_values[0] . $element;
    $new_element_2 = $new_values[1] . $element;
    $new_element_3 = $new_values[2] . $element;
    
    // Add the new concatenated strings to the new array
    $new_array[] = $new_element_1;
    $new_array[] = $new_element_2;
    $new_array[] = $new_element_3;
}


$duplicates = array_intersect($urls, $new_array);

// Include the send_email.php script if there are missing pages
if (!empty($duplicates)) {
    require_once('/var/www/vhosts/ypsilon.dev/cron/send_email.php');

    // Send an email notification with all the URLs
    send_email($duplicates);
}
