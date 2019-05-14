<?php

namespace SheDied\helpers;

use SheDied\helpers\Numbers;
use SheDied\PojokJogjaController;

/**
 * technoreview.us
 */
class Empat extends Numbers {

    const TECHNOREVIEW_US = 'technoreview.us';

    public function fetchPostLinks(PojokJogjaController $controller) {
        ;
    }

    public function switchParsers(PojokJogjaController $controller) {
        ;
    }

    static public function sources() {
        #asus
        $sources[10] = ['name' => 'Asus: ZenBook', 'url' => 'https://www.asus.com/OfficialSiteAPI.asmx/GetModelResults?WebsiteId=1&ProductLevel2Id=155&FiltersCategory=35305&Filters=&Sort=3'];

        return$sources;
    }

}
