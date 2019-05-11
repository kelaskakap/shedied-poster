<?php

namespace SheDied\helpers;

use SheDied\helpers\Numbers;
use SheDied\PojokJogjaController;

/**
 * pojokjogja.com
 */
class Tiga extends Numbers {

    const POJOKJOGJA_COM = 'pojokjogja.com';

    public function fetchPostLinks(PojokJogjaController $controller) {
        
    }

    public function switchParsers(PojokJogjaController $controller) {
        
    }

    static public function sources() {

        $sources[2] = ['name' => 'Home Designing: Living Room Designs', 'url' => 'http://www.home-designing.com/category/living-room-design/'];
        $sources[3] = ['name' => 'Home Designing: Bedroom Designs', 'url' => 'http://www.home-designing.com/category/bedroom-designs/'];

        return $sources;
    }

}
