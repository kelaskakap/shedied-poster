<?php

namespace SheDied\helpers;

use SheDied\PojokJogjaController;

abstract class Numbers {

    protected $parser = '';
    protected $need_gallery = false;

    public function getParser() {

        return $this->parser;
    }

    abstract public function fetchPostLinks(PojokJogjaController $controller);

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

}
