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

        if ($this->source_ONEKINDESIGN($controller)) {

            foreach (pq('article.single-post > h2 a') as $a) {

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
            $this->parser = 'SheDied\parser\HomeDesigningParser';
        elseif ($this->source_ONEKINDESIGN($controller))
            $this->parser = 'SheDied\parser\OneKinDesignParser';
    }

    static public function sources() {

        $sources = self::sources_homedesign();
        $sources += self::sources_onekindesign();

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

    protected function source_ONEKINDESIGN(PojokJogjaController $controller) {

        return $controller->getNewsSrc() > 21 && $controller->getNewsSrc() < 58;
    }

    public function fetchCustomUrls(PojokJogjaController $controller) {
        ;
    }

    public function scanURL(PojokJogjaController $controller) {
        ;
    }

    protected static function sources_homedesign() {

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
        //$sources[] = ['name' => '', 'url' => ''];

        return $sources;
    }

    static protected function sources_onekindesign() {

        $sources[22] = ['name' => 'One Kin Design: Barn Homes', 'url' => 'https://onekindesign.com/tag/barn-house/'];
        $sources[23] = ['name' => 'One Kin Design: Beach House', 'url' => 'https://onekindesign.com/tag/beach-house/'];
        $sources[24] = ['name' => 'One Kin Design: Cabin', 'url' => 'https://onekindesign.com/tag/cabin/'];
        $sources[25] = ['name' => 'One Kin Design: Contemporary Homes', 'url' => 'https://onekindesign.com/tag/contemporary/'];
        $sources[26] = ['name' => 'One Kin Design: Cottage', 'url' => 'https://onekindesign.com/tag/cottage/'];
        $sources[28] = ['name' => 'One Kin Design: Farm House', 'url' => 'https://onekindesign.com/tag/farm-house/'];
        $sources[29] = ['name' => 'One Kin Design: Mediterranean Homes', 'url' => 'https://onekindesign.com/tag/mediterranean/'];
        $sources[30] = ['name' => 'One Kin Design: Mid Century Homes', 'url' => 'https://onekindesign.com/tag/mid-century/'];
        $sources[31] = ['name' => 'One Kin Design: Modern Homes', 'url' => 'https://onekindesign.com/tag/modern/'];
        $sources[32] = ['name' => 'One Kin Design: Mountain Homes', 'url' => 'https://onekindesign.com/tag/mountain-home/'];
        $sources[33] = ['name' => 'One Kin Design: Scandinavian Homes', 'url' => 'https://onekindesign.com/tag/scandinavian/'];
        $sources[34] = ['name' => 'One Kin Design: Sustainable Homes', 'url' => 'https://onekindesign.com/tag/sustainable/'];

        $sources[35] = ['name' => 'One Kin Design: Loft', 'url' => 'https://onekindesign.com/tag/loft/'];
        $sources[36] = ['name' => 'One Kin Design: Penthouse', 'url' => 'https://onekindesign.com/tag/penthouse/'];
        $sources[37] = ['name' => 'One Kin Design: Warehouse', 'url' => 'https://onekindesign.com/tag/warehouse/'];

        $sources[38] = ['name' => 'One Kin Design: Basement Design', 'url' => 'https://onekindesign.com/tag/basement-design/'];
        $sources[39] = ['name' => 'One Kin Design: Bathroom', 'url' => 'https://onekindesign.com/tag/bathroom/'];
        $sources[40] = ['name' => 'One Kin Design: Bedroom', 'url' => 'https://onekindesign.com/tag/bedroom/'];
        $sources[41] = ['name' => 'One Kin Design: Closet', 'url' => 'https://onekindesign.com/tag/closet/'];
        $sources[42] = ['name' => 'One Kin Design: Dining Room', 'url' => 'https://onekindesign.com/tag/dining/'];
        $sources[43] = ['name' => 'One Kin Design: Home Bar', 'url' => 'https://onekindesign.com/tag/home-bar/'];
        $sources[44] = ['name' => 'One Kin Design: Home Office', 'url' => 'https://onekindesign.com/tag/home-office/'];

        $sources[45] = ['name' => 'One Kin Design: Outdoor Inspiration', 'url' => 'https://onekindesign.com/tag/outdoor-inspiration/'];
        $sources[46] = ['name' => 'One Kin Design: Spring Decor', 'url' => 'https://onekindesign.com/tag/spring-decor/'];
        $sources[47] = ['name' => 'One Kin Design: Fall Decorating', 'url' => 'https://onekindesign.com/tag/fall-decorating/'];
        $sources[48] = ['name' => 'One Kin Design: Halloween Decorating Ideas', 'url' => 'https://onekindesign.com/tag/halloween-decorating-ideas/'];
        $sources[49] = ['name' => 'One Kin Design: Thanksgiving Decor', 'url' => 'https://onekindesign.com/tag/thanksgiving-decor/'];
        $sources[50] = ['name' => 'One Kin Design: Christmas Inspiration', 'url' => 'https://onekindesign.com/tag/christmas-inspiration/'];

        $sources[51] = ['name' => 'One Kin Design: Furniture', 'url' => 'https://onekindesign.com/category/furniture/'];
        $sources[52] = ['name' => 'One Kin Design: Garden', 'url' => 'https://onekindesign.com/tag/garden/'];
        $sources[53] = ['name' => 'One Kin Design: Real Estate', 'url' => 'https://onekindesign.com/category/real-estate-2/'];
        $sources[54] = ['name' => 'One Kin Design: Readers Homes', 'url' => 'https://onekindesign.com/category/readers-homes/'];
        $sources[55] = ['name' => 'One Kin Design: Swimming Pool', 'url' => 'https://onekindesign.com/tag/swimming-pool/'];
        $sources[56] = ['name' => 'One Kin Design: Travel', 'url' => 'https://onekindesign.com/category/travel/'];
        $sources[57] = ['name' => 'One Kin Design: Vacation Rental', 'url' => 'https://onekindesign.com/tag/vacation-rental/'];
        
        //$sources[] = ['name' => '', 'url' => ''];

        return $sources;
    }

}
