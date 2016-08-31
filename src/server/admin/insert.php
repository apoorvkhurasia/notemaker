<?php
include "../../lib/Utils.php";
include "../resmgmt.php";

//API parameters
$topicKey = $_POST["topicKey"];
$markdownText = $_POST["markdown"];
$chapterName = $_POST["chapterName"];
$chapterKey = $_POST["chapterKey"];

$fileToDelete = null;

if(isset($topicKey) && isset($markdownText)) {
    //API to set chapter text.
    if (!isset($chapterName)) {
        exitWithHttpStatusCode(400); //Bad API parameters; chapterName is mandatory
    }
    
    if (!isset($chapterKey)) {
        //We intend to create a new chapter; generate a key and append at the end (max index + 1)
        $chapterKey = (getMaximumChapterIndex($topicKey) + 1) . "." . str_replace(" ", ".", $chapterName) . ".md";
    } else {
        //We intend to edit an existing chapter
        $topic = new Topic($topicKey);
        $existingChapter = new Chapter($topic, $chapterKey);
        if (strcmp($existingChapter->displayName, $chapterName) != 0) {
            //We need to replace an existing file and create a new one in its place (respecting the index)
            $fileToDelete = getContentFile($topicKey, $chapterKey);
            $chapterKey = $existingChapter->index . "." . str_replace(" ", ".", $chapterName) . ".md"; //New key
        }
    }
    
    //Create if missing
    if(!createChapterDir($topicKey)) {
        exitWithHttpStatusCode(500);
    } 
    
    if (file_put_contents(getContentFile($topicKey, $chapterKey), $markdownText)) {
        if (isset($fileToDelete) && file_exists($fileToDelete) && !unlink($fileToDelete)) {
            exitWithHttpStatusCode(500);
        } else {
            header("Content-type: application/json");
            echo getChapter($topicKey, $chapterKey);
            http_response_code(201);
            exit();
        }
    } else {
        exitWithHttpStatusCode(500);
    }
} else {
    exitWithHttpStatusCode(400);   
}
?>
