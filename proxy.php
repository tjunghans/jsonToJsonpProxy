<?php
/**
 * Helper for cleaning up strings
 *
 * @return string
 */
function sanitize($str) {
    return htmlspecialchars($str, ENT_QUOTES);
}

/**
 * Converts associative array to query string => ?foo=bar&baz=quux
 *
 * @return string
 */
function httpBuildQuery($params) {

    $pairs = array();

    foreach($params as $key => $val) {
        $pairs[] = $key . '=' . $val;
    }

    return '?' . implode('&', $pairs);

}

/**
 * Fetches all the $_GET[] parameters filtering out "url" and "callback"
 * 
 * @return string
 */
function prepareGetParams() {
    $requestParams = array();

    $ignoredRequestParams = array(
        'url',
        'callback'
        );

    foreach($_GET as $key => $val) {
        if (in_array($key, $ignoredRequestParams)) {
            continue;
        }

        $requestParams[sanitize($key)] = urlencode(sanitize($val));

    }

    return httpBuildQuery($requestParams);    
}

/**
 * Makes a get request using CURL
 * 
 * @param  string $requestUrl
 * @return array
 */
function makeHttpRequest($requestUrl) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $requestUrl);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // We simply expect json data here
    $jsonData = curl_exec($ch);

     /* Check HTTP Code */
    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    /* Close cURL Resource */
    curl_close($ch);

    return array(
        "jsonData" => $jsonData,
        "httpStatus" => $httpStatus
        );
}

/**
 * The main function
 * 
 * @return void
 */
function main() {
    $requestUrl = sanitize($_GET['url']) . prepareGetParams();
    $jsonpCallback = sanitize($_GET['callback']);

    $response = makeHttpRequest($requestUrl);
    $httpStatus = $response["httpStatus"];
    $jsonData = $response["jsonData"];

    /* 200 Response! */
    if ($httpStatus == 200) {

        header("content-type: application/javascript;charset=UTF-8");
        echo $jsonpCallback . '(' . $jsonData . ');';
        exit;

    } else {

        /* Debug */
        var_dump($data);
        var_dump($status);
        die('error!');

    }
}

// START
// This script expects two query string params: url and callback
if (!isset($_GET['url']) || !isset($_GET['callback'])) {
	exit('You need to specify and an url and the callback parameter');
}

main();
