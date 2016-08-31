<?php

//Copied from http://stackoverflow.com/a/10473026
function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}

//Copied from http://stackoverflow.com/a/10473026
function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function exitWithHttpStatusCode($code) {
    http_response_code($code);
    exit();
}

function returnJsonResp($resp) {
    header("Content-type: application/json");
    echo json_encode($resp);
}

function array_filter_map($array, $filterCallback, $mapCallback) {
    return array_map($mapCallback, array_filter($array, $filterCallback));
}

?>
