<?php

namespace SheDied\helpers;

use SheDied\helpers\Numbers;
use SheDied\PojokJogjaController;

/**
 * soundwooden.co
 */
class Wolu extends Numbers
{

    const SWOODEN = 'soundwooden.co';

    public function __construct()
    {
        $this->set_Need_Gallery(true);
    }

    public function fetchPostLinks(PojokJogjaController $controller)
    {
        $doc = $this->fetchLinks($controller->getUrl());

        \phpQuery::newDocument($doc);

        $postlinks = [];

        if ($this->source_TRENDIR($controller))
        {
            foreach (pq('article div.post-title a') as $a)
            {
                $link = pq($a)->attr('href');
                $title = pq($a)->elements[0]->nodeValue;
                $postlinks[] = array("title" => trim($title), "link" => trim($link), 'src' => $controller->getNewsSrc(), 'cat' => $controller->getCategory());

                if ($this->enough($postlinks, $controller))
                {
                    break;
                }
            }
        }

        if ($this->source_DESIGNMILK($controller))
        {
            foreach (pq('div.article-content h3 a') as $a)
            {
                $link = pq($a)->attr('href');
                $title = pq($a)->elements[0]->nodeValue;
                $postlinks[] = array("title" => trim($title), "link" => trim($link), 'src' => $controller->getNewsSrc(), 'cat' => $controller->getCategory());

                if ($this->enough($postlinks, $controller))
                {
                    break;
                }
            }
        }

        if ($this->source_INSPIREDBYTHIS($controller))
        {
            foreach (pq('div.imglist div.imgtxtbox a') as $a)
            {
                $link = pq($a)->attr('href');
                $title = pq($a)->elements[0]->nodeValue;
                $postlinks[] = array("title" => trim($title), "link" => trim($link), 'src' => $controller->getNewsSrc(), 'cat' => $controller->getCategory());

                if ($this->enough($postlinks, $controller))
                {
                    break;
                }
            }
        }

        if ($this->source_CONTEMPORIST($controller))
        {
            foreach (pq('article > h2.title a') as $a)
            {
                $link = pq($a)->attr('href');
                $title = pq($a)->elements[0]->nodeValue;
                $postlinks[] = array("title" => trim($title), "link" => trim($link), 'src' => $controller->getNewsSrc(), 'cat' => $controller->getCategory());

                if ($this->enough($postlinks, $controller))
                {
                    break;
                }
            }
        }

        $controller->setPostLinks($postlinks);
    }

    public function switchParsers(PojokJogjaController $controller)
    {
        if ($this->source_TRENDIR($controller))
            $this->parser = 'SheDied\parser\TrendirParser';
        elseif ($this->source_DESIGNMILK($controller))
            $this->parser = 'SheDied\parser\DesignMilkParser';
        elseif ($this->source_INSPIREDBYTHIS($controller))
            $this->parser = 'SheDied\parser\InspiredByThisParser';
        elseif ($this->source_CONTEMPORIST($controller))
            $this->parser = 'SheDied\parser\ContemporistParser';
    }

    static public function sources()
    {
        $sources = self::sources_trendir();
        $sources += self::sources_contemporist();
        $sources += self::sources_designmilk();
        $sources += self::sources_inspiredbythis();

        return $sources;
    }

    public function firstRunURL($url, $sourceId, PojokJogjaController $controller)
    {
        if (empty($this->fr) && !$this->isfr)
            return $url;

        $default = 50;
        if ($this->source_CONTEMPORIST($controller))
        {
            $default = 100;
        }

        $t = isset($this->fr[$sourceId]) ? (int) $this->fr[$sourceId] : $default;
        $Page = $t > 1 ? 'page/' . $t : '';

        $t--;
        $this->fr[$sourceId] = $t;

        return $url . $Page;
    }

    protected function source_TRENDIR(PojokJogjaController $controller)
    {
        return $controller->getNewsSrc() > 1 && $controller->getNewsSrc() < 12;
    }

    protected function source_DESIGNMILK(PojokJogjaController $controller)
    {
        return $controller->getNewsSrc() > 11 && $controller->getNewsSrc() < 17;
    }

    protected function source_INSPIREDBYTHIS(PojokJogjaController $controller)
    {
        return $controller->getNewsSrc() > 16 && $controller->getNewsSrc() < 22;
    }

    protected function source_CONTEMPORIST(PojokJogjaController $controller)
    {
        return $controller->getNewsSrc() > 21 && $controller->getNewsSrc() < 26;
    }

    public function fetchCustomUrls(PojokJogjaController $controller)
    {
        ;
    }

    public function scanURL(PojokJogjaController $controller, $params = array())
    {
        ;
    }

    protected static function sources_trendir()
    {
        $sources[2] = ['name' => 'Trendir: Wood Homes', 'url' => 'https://www.trendir.com/house-design/wood-homes/'];
        $sources[3] = ['name' => 'Trendir: Kitchen', 'url' => 'https://www.trendir.com/kitchen/'];
        $sources[6] = ['name' => 'Trendir: Furniture', 'url' => 'https://www.trendir.com/furniture/'];
        $sources[7] = ['name' => 'Trendir: Flooring', 'url' => 'https://www.trendir.com/flooring/'];
        $sources[9] = ['name' => 'Trendir: Bathrooms', 'url' => 'https://www.trendir.com/bathroom/'];
        $sources[10] = ['name' => 'Trendir: Interiors', 'url' => 'https://www.trendir.com/interiors/'];
        $sources[11] = ['name' => 'Trendir: Decors', 'url' => 'https://www.trendir.com/decor-accents/'];

        return $sources;
    }

    protected static function sources_designmilk()
    {
        $sources[12] = ['name' => 'Design Milk: Architecture', 'url' => 'https://design-milk.com/category/architecture/'];
        $sources[13] = ['name' => 'Design Milk: Home Furnishing', 'url' => 'https://design-milk.com/category/home-furnishings/'];
        $sources[14] = ['name' => 'Design Milk: Interior Designs', 'url' => 'https://design-milk.com/category/interior-design/'];
        $sources[15] = ['name' => 'Design Milk: Art', 'url' => 'https://design-milk.com/category/art/'];
        $sources[16] = ['name' => 'Design Milk: At the Office', 'url' => 'https://design-milk.com/column/at-the-office/'];

        return $sources;
    }

    static protected function sources_inspiredbythis()
    {
        $sources[17] = ['name' => 'Inspired By This: Decor', 'url' => 'http://www.inspiredbythis.com/category/dwell/home-decor/'];
        $sources[18] = ['name' => 'Inspired By This: Home Tours', 'url' => 'http://www.inspiredbythis.com/category/dwell/home-tours/'];
        $sources[19] = ['name' => 'Inspired By This: Baby Showers', 'url' => 'http://www.inspiredbythis.com/category/grow/baby-showers/'];
        $sources[20] = ['name' => 'Inspired By This: Office Tours', 'url' => 'http://www.inspiredbythis.com/category/business/office-tours/'];
        $sources[21] = ['name' => 'Inspired By This: Office Life', 'url' => 'http://www.inspiredbythis.com/category/business/office-life/'];

        return $sources;
    }

    static protected function sources_contemporist()
    {
        $sources[22] = ['name' => 'Contemporist : Architecture', 'url' => 'https://www.contemporist.com/category/architecture/'];
        $sources[23] = ['name' => 'Contemporist : Interiors', 'url' => 'https://www.contemporist.com/category/interiors/'];
        $sources[24] = ['name' => 'Contemporist : Design', 'url' => 'https://www.contemporist.com/category/design/'];
        $sources[25] = ['name' => 'Contemporist : Art', 'url' => 'https://www.contemporist.com/category/art/'];

        return $sources;
    }

    public function getIdentity()
    {
        return static::SWOODEN;

        array(
            'source_11' =>
            array(
                11 => 8,
            ),
            'source_10' =>
            array(
                10 => 23,
            ),
            'source_9' =>
            array(
                9 => 42,
            ),
            'source_7' =>
            array(
                7 => -5,
            ),
            'source_6' =>
            array(
                6 => 30,
            ),
            'source_3' =>
            array(
                3 => 13,
            ),
            'source_2' =>
            array(
                2 => -3,
            ),
            'source_25' =>
            array(
                25 => 100,
            ),
            'source_24' =>
            array(
                24 => 100,
            ),
            'source_23' =>
            array(
                23 => 100,
            ),
            'source_22' =>
            array(
                22 => 100,
            ),
            'source_21' =>
            array(
                21 => 50,
            ),
            'source_20' =>
            array(
                20 => 50,
            ),
            'source_19' =>
            array(
                19 => 50,
            ),
            'source_18' =>
            array(
                18 => 50,
            ),
            'source_17' =>
            array(
                17 => 50,
            ),
            'source_16' =>
            array(
                16 => 50,
            ),
            'source_15' =>
            array(
                15 => 50,
            ),
            'source_14' =>
            array(
                14 => 50,
            ),
            'source_13' =>
            array(
                13 => 50,
            ),
            'source_12' =>
            array(
                12 => 50,
            ),
        );
    }

}
