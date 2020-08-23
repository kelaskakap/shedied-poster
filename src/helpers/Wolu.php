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
        
        $controller->setPostLinks($postlinks);
    }

    public function switchParsers(PojokJogjaController $controller)
    {
        if ($this->source_TRENDIR($controller))
            $this->parser = 'SheDied\parser\TrendirParser';
    }

    static public function sources()
    {
        $sources = self::sources_trendir();

        return $sources;
    }

    public function firstRunURL($url, $sourceId, PojokJogjaController $controller)
    {
        if (empty($this->fr) && !$this->isfr)
            return $url;

        $default = 50;
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

    public function getIdentity()
    {
        return static::SWOODEN;
    }

}
