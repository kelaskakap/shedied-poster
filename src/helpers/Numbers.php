<?php

namespace SheDied\helpers;

use SheDied\PojokJogjaController;

abstract class Numbers {

    protected $parser = '';
    protected $need_gallery = false;
    protected $fr = []; // array first run
    protected $isfr = FALSE; //is first run
    protected $post_type = 'post';
    protected $query_param = [];

    public function getParser() {

        return $this->parser;
    }

    abstract public function fetchPostLinks(PojokJogjaController $controller);

    abstract public function fetchCustomUrls(PojokJogjaController $controller);

    abstract public function switchParsers(PojokJogjaController $controller);

    public function need_Gallery() {
        return $this->need_gallery;
    }

    public function set_Need_Gallery($need) {
        $this->need_gallery = (bool) $need;
    }

    public function enough($links = [], PojokJogjaController $controller) {

        return count($links) >= $controller->getCount();
    }

    public function firstRunURL($url, $sourceId, PojokJogjaController $controller) {

        if (empty($this->fr))
            return $url;
    }

    public function yesFirstRun($fr) {

        $this->fr = $fr;
        $this->isfr = TRUE;
    }

    public function arrFirstRun() {

        return $this->fr;
    }

    protected function fetchLinks($url) {

        $doc = @file_get_contents($url);
        if (function_exists('mb_convert_encoding')) {
            $doc = mb_convert_encoding($doc, "HTML-ENTITIES", "UTF-8");
        }

        return $doc;
    }

    public function setPostType($type) {

        $this->post_type = $type;
    }

    public function getPostType() {

        return $this->post_type;
    }

    abstract public function scanURL(PojokJogjaController $controller, $params = []);
    
    abstract public function getIdentity();
    
    public function getQuery_Param() {
        return $this->query_param;
    }
}
