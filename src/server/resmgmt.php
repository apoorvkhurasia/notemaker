<?php

function getContentRoot() {
    return "__CONTENT_BASE_DIR__";
}

function getContentDir($topicKey) {
    return getContentRoot() . "/$topicKey/chapters";
}

function getContentFile($topicKey, $chapterKey) {
    return getContentDir($topicKey) . "/$chapterKey";
}

function getMaximumChapterIndex($topicKey) {
    //We intend to create a new chapter
    $allFiles = scandir(getContentDir($topicKey));
    $maxIndex = 0;
    foreach($allFiles as $index => $fileName) {
        if (endsWith($fileName, ".md")) {
            $index = explode(".", $fileName)[0];
            if ($index > $maxIndex) {
                $maxIndex = $index;
            }
        }
    }
    return $maxIndex;
}

function createChapterDir($topicKey) {
    $topicDirName = getContentDir($topicKey);
    if (is_dir($topicDirName) || mkdir($topicDirName, 0775, true)) {
        return $topicDirName;
    }
    return false; //PHP convention for failure
}

function getChapters($topicKey) {
    $topicDirName = getContentDir($topicKey);
    $chapters = array();
    if (is_dir($topicDirName)) {
        $files = scandir($topicDirName);
        sort($files);
        $topic = new Topic($topicKey);
        foreach($files as $index => $fileName) {
            if (endsWith($fileName, ".md")) {
                array_push($chapters, new Chapter($topic, $fileName));
            }
        }
    }
    return $chapters;
}

function getAllTopics() {
    $topics = array_filter_map(scandir(getContentRoot()), 
       function ($fileOrDirName) {
           return is_dir(getContentDir($fileOrDirName)) && !startsWith($fileOrDirName, ".");
       }, 
       function($dirName) {
           return new Topic($dirName);
       });
    $resp = new stdClass();
    $resp->topics = $topics;
    $resp->links = array("self" => "__NORMAL_BASE_URL__/server/reslookup.php");
    return json_encode($resp);
}

function getTopic($topicKey) {
    $topicDirName = getContentDir($topicKey);
    
    $resp = new stdClass();
    $resp->topic = new Topic($topicKey);
    $resp->chapters = getChapters($topicKey);
    $resp->links = array("self" => "__NORMAL_BASE_URL__/server/reslookup.php?topic=$topicKey");
    return json_encode($resp);
} 

function getChapter($topicKey, $chapterKey) {
    $chapterContents = file_get_contents(getContentFile($topicKey, $chapterKey), FILE_USE_INCLUDE_PATH);
    
    $resp = new stdClass();
    $resp->allChapters = getChapters($topicKey);
    $resp->chapter = new Chapter(new Topic($topicKey), $chapterKey);
    $resp->content = $chapterContents;
    $resp->links = array("self" => "__NORMAL_BASE_URL__/server/reslookup.php?topic=$topicKey&chapter=$chapterFile");
    return json_encode($resp);
}

class LinkAware {
    public $links;
    
    public function __construct() {
        $this->links = array();
    }
    
    public function addLink($title, $href) {
        $this->links[$title] = $href;
    }
    
    public function getLink($title) {
        return $this->links[$title];
    }
}

class Topic extends LinkAware {
    public $topicKey;
    public $displayName;
        
    public function __construct($topicKey) {
        parent::__construct();
        $this->topicKey = $topicKey;
        $this->displayName = str_replace(".", " ", $topicKey);
        $this->addLink("get_chapters", "__NORMAL_BASE_URL__/server/reslookup.php?topic=$topicKey");
    }
    
}

class Chapter extends LinkAware {
    public $chapterKey;
    public $topic;
    public $index;
    public $displayName;
    
    public function __construct(Topic $topic, $chapterKey) {
        parent::__construct();
        $this->topic = $topic;
        $this->chapterKey = $chapterKey;
        
        $keyParts = explode(".", $chapterKey);
        $this->index = $keyParts[0];
        $this->displayName = implode(" ", array_splice($keyParts, 1, sizeof($keyParts) - 2));
        
        $topicKey = $topic->topicKey;
        $this->addLink("get_content", "__NORMAL_BASE_URL__/server/reslookup.php?topic=$topicKey&chapter=$chapterKey");
    }
}

?>
