<?php

namespace SheDied\helpers;

use SheDied\helpers\Numbers;
use SheDied\PojokJogjaController;

/**
 * furnitureideas.us
 */
class Tujuh extends Numbers {

    const FURNITUREIDEAS_US = 'furnitureideas.us';

    public function __construct() {

        $this->set_Need_Gallery(true);
    }

    public function fetchPostLinks(PojokJogjaController $controller) {

        $doc = $this->fetchLinks($controller->getUrl());

        \phpQuery::newDocument($doc);

        $postlinks = [];

        if ($this->source_DESIGNMILK($controller)) {

            foreach (pq('div.article-content h3 a') as $a) {

                $link = pq($a)->attr('href');
                $title = pq($a)->elements[0]->nodeValue;
                $postlinks[] = array("title" => trim($title), "link" => trim($link), 'src' => $controller->getNewsSrc(), 'cat' => $controller->getCategory());

                if ($this->enough($postlinks, $controller)) {

                    break;
                }
            }
        }
        file_put_contents('/tmp/oni.txt', var_export($postlinks, true));
        $controller->setPostLinks($postlinks);
    }

    public function switchParsers(PojokJogjaController $controller) {

        if ($this->source_DESIGNMILK($controller))
            $this->parser = 'SheDied\parser\DesignMilkParser';
    }

    static public function sources() {

        $sources = self::sources_designmilk();
        //$sources += self::sources_onekindesign();

        return $sources;
    }

    public function firstRunURL($url, $sourceId, PojokJogjaController $controller) {

        if (empty($this->fr))
            return $url;

        $t = isset($this->fr[$sourceId]) ? (int) $this->fr[$sourceId] : 100;
        $Page = $t > 1 ? 'page/' . $t : '';

        $t--;
        $this->fr[$sourceId] = $t;

        return $url . $Page;
    }

    protected function source_DESIGNMILK(PojokJogjaController $controller) {

        return $controller->getNewsSrc() > 1 && $controller->getNewsSrc() < 13;
    }

    public function fetchCustomUrls(PojokJogjaController $controller) {
        ;
    }

    public function scanURL(PojokJogjaController $controller, $params = array()) {
        ;
    }

    protected static function sources_designmilk() {

        $sources[2] = ['name' => 'Design Milk: Architecture', 'url' => 'https://design-milk.com/category/architecture/'];
        $sources[3] = ['name' => 'Design Milk: Home Furnishing', 'url' => 'https://design-milk.com/category/home-furnishings/'];
        $sources[6] = ['name' => 'Design Milk: Interior Designs', 'url' => 'https://design-milk.com/category/interior-design/'];
        $sources[7] = ['name' => 'Design Milk: Art', 'url' => 'https://design-milk.com/category/art/'];
        $sources[9] = ['name' => 'Design Milk: Lifestyle', 'url' => 'https://design-milk.com/category/lifestyle/'];
        $sources[10] = ['name' => 'Design Milk: Pets', 'url' => 'https://design-milk.com/category/pets/'];
        $sources[11] = ['name' => 'Design Milk: At the Office', 'url' => 'https://design-milk.com/column/at-the-office/'];
        $sources[12] = ['name' => 'Design Milk: Destination Design', 'url' => 'https://design-milk.com/column/destination-design/'];
        //$sources[] = ['name' => '', 'url' => ''];

        return $sources;
    }

    public function getIdentity() {

        return static::FURNITUREIDEAS_US;
    }

}
