<?php 
include "../../lib/Utils.php";
include "../resmgmt.php";

$topicName = $_POST["topicName"];

if (!isset($topicName)) {
    exitWithHttpStatusCode(400); //Topic name is mandatory
}

$topicKey = str_replace(" ", ".", $topicName);
if(createChapterDir($topicKey)) {
    header("Content-type: application/json");
    echo getTopic($topicKey);
    http_response_code(201);
    exit();
} else {
    exitWithHttpStatusCode(500);
}
?>
