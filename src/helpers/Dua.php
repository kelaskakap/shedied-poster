<?php

namespace SheDied\helpers;

use SheDied\helpers\Numbers;
use SheDied\PojokJogjaController;

/**
 * awesomedecors.us
 */
class Dua extends Numbers {

    const AWESOMEDECORS_US = 'awesomedecors.us';

    public function __construct() {

        $this->set_Need_Gallery(true);
    }

    public function fetchPostLinks(PojokJogjaController $controller) {

        $doc = $this->fetchLinks($controller->getUrl());

        \phpQuery::newDocument($doc);

        $postlinks = [];

        if ($this->source_HOMEDESIGNING($controller)) {

            foreach (pq('div.heading a') as $a) {

                $link = pq($a)->attr('href');
                $title = pq($a)->elements[0]->nodeValue;
                $postlinks[] = array("title" => trim($title), "link" => trim($link), 'src' => $controller->getNewsSrc(), 'cat' => $controller->getCategory());

                if ($this->enough($postlinks, $controller)) {

                    break;
                }
            }
        }

        $controller->setPostLinks($postlinks);
    }

    public function switchParsers(PojokJogjaController $controller) {

        if ($this->source_HOMEDESIGNING($controller))
            $this->parser = 'SheDied\parser\HomeDesigning';
    }

    static public function sources() {

        $sources[2] = ['name' => 'Home Designing: Living Room Designs', 'url' => 'http://www.home-designing.com/category/living-room-design/'];
        $sources[3] = ['name' => 'Home Designing: Bedroom Designs', 'url' => 'http://www.home-designing.com/category/bedroom-designs/'];
        $sources[6] = ['name' => 'Home Designing: Bathroom Designs', 'url' => 'http://www.home-designing.com/category/bathroom-designs/'];
        $sources[7] = ['name' => 'Home Designing: Kitchen Designs', 'url' => 'http://www.home-designing.com/category/kitchen-designs/'];
        $sources[9] = ['name' => 'Home Designing: Teen Room Designs', 'url' => 'http://www.home-designing.com/category/teen-room-designs/'];
        $sources[10] = ['name' => 'Home Designing: Furniture and Accessories', 'url' => 'http://www.home-designing.com/category/furniture-designs/'];
        $sources[11] = ['name' => 'Home Designing: Accessories', 'url' => 'http://www.home-designing.com/category/accessories/'];
        $sources[12] = ['name' => 'Home Designing: Designs by Style', 'url' => 'http://www.home-designing.com/category/designs-by-style/'];
        $sources[13] = ['name' => 'Home Designing: Decoration', 'url' => 'http://www.home-designing.com/category/decoration/'];
        $sources[14] = ['name' => 'Home Designing: Dining Room Designs', 'url' => 'http://www.home-designing.com/category/dining-room-designs/'];
        $sources[16] = ['name' => 'Home Designing: Home Office Designs', 'url' => 'http://www.home-designing.com/category/home-office-designs/'];
        $sources[17] = ['name' => 'Home Designing: House Tours', 'url' => 'http://www.home-designing.com/category/house-tours/'];
        $sources[18] = ['name' => 'Home Designing: Kids Room Designs', 'url' => 'http://www.home-designing.com/category/kids-room-design/'];
        $sources[19] = ['name' => 'Home Designing: Luxury', 'url' => 'http://www.home-designing.com/category/luxury/'];
        $sources[20] = ['name' => 'Home Designing: Non-Residential', 'url' => 'http://www.home-designing.com/category/non-residential/'];
        $sources[21] = ['name' => 'Home Designing: Technology At Home', 'url' => 'http://www.home-designing.com/category/technology-at-home/'];

        return $sources;
    }

    public function firstRunURL($url, $sourceId, PojokJogjaController $controller) {

        parent::firstRunURL($url, $sourceId);

        $t = isset($this->fr[$sourceId]) ? (int) $this->fr[$sourceId] : 50;
        $Page = $t > 1 ? 'page/' . $t : '';

        $t--;
        $this->fr[$sourceId] = $t;

        return $url . $Page;
    }

    protected function source_HOMEDESIGNING(PojokJogjaController $controller) {

        return $controller->getNewsSrc() > 1 && $controller->getNewsSrc() < 22;
    }

    public function fetchCustomUrls(PojokJogjaController $controller) {
        ;
    }

    public function scanURL(PojokJogjaController $controller) {
        ;
    }

}
