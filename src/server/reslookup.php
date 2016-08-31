<?php     

include "../lib/Utils.php";
include "./resmgmt.php";

//If nothing is given we return topics
if (!isset($_GET["topic"])) {
    header("Content-type: application/json");
    echo getAllTopics();
    http_response_code(200);
    exit();
} else if (!isset($_GET["chapter"])) {
    header("Content-type: application/json");
    echo getTopic($_GET["topic"]);
    http_response_code(200);
    exit();
} else {
    header("Content-type: application/json");
    echo getChapter($_GET["topic"], $_GET["chapter"]);
    http_response_code(200);
    exit();
}
?>
