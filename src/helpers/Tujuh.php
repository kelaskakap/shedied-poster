<?php

namespace SheDied\helpers;

use SheDied\helpers\Numbers;
use SheDied\PojokJogjaController;

/**
 * furnitureideas.us
 */
class Tujuh extends Numbers
{

    const FURNITUREIDEAS_US = 'furnitureideas.us';

    public function __construct()
    {
        $this->set_Need_Gallery(true);
    }

    public function fetchPostLinks(PojokJogjaController $controller)
    {
        $doc = $this->fetchLinks($controller->getUrl());

        \phpQuery::newDocument($doc);

        $postlinks = [];

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
        if ($this->source_DESIGNMILK($controller))
            $this->parser = 'SheDied\parser\DesignMilkParser';
        elseif ($this->source_INSPIREDBYTHIS($controller))
            $this->parser = 'SheDied\parser\InspiredByThisParser';
        elseif ($this->source_CONTEMPORIST($controller))
            $this->parser = 'SheDied\parser\ContemporistParser';
    }

    static public function sources()
    {
        $sources = self::sources_designmilk();
        $sources += self::sources_inspiredbythis();
        $sources += self::sources_contemporist();

        return $sources;
    }

    public function firstRunURL($url, $sourceId, PojokJogjaController $controller)
    {
        if (empty($this->fr) && !$this->isfr)
            return $url;

        if ($this->source_CONTEMPORIST($controller))
            $default = 2000;
        else
            $default = 100;

        $t = isset($this->fr[$sourceId]) ? (int) $this->fr[$sourceId] : $default;
        $Page = $t > 1 ? 'page/' . $t : '';

        $t--;
        $this->fr[$sourceId] = $t;

        return $url . $Page;
    }

    protected function source_DESIGNMILK(PojokJogjaController $controller)
    {
        return $controller->getNewsSrc() > 1 && $controller->getNewsSrc() < 13;
    }

    protected function source_INSPIREDBYTHIS(PojokJogjaController $controller)
    {
        return $controller->getNewsSrc() > 12 && $controller->getNewsSrc() < 32;
    }

    protected function source_CONTEMPORIST(PojokJogjaController $controller)
    {
        return $controller->getNewsSrc() > 32 && $controller->getNewsSrc() < 37;
    }

    public function fetchCustomUrls(PojokJogjaController $controller)
    {
        ;
    }

    public function scanURL(PojokJogjaController $controller, $params = array())
    {
        ;
    }

    protected static function sources_designmilk()
    {
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

    static protected function sources_inspiredbythis()
    {
        $sources[13] = ['name' => 'Inspired By This: Wedding Plan', 'url' => 'http://www.inspiredbythis.com/category/wed/planning/'];
        $sources[14] = ['name' => 'Inspired By This: Wedding Celebrate', 'url' => 'http://www.inspiredbythis.com/category/wed/wedding-celebrations/'];
        $sources[15] = ['name' => 'Inspired By This: Wedding Engagements', 'url' => 'http://www.inspiredbythis.com/category/wed/engagements/'];
        $sources[16] = ['name' => 'Inspired By This: Wedding The Weddings', 'url' => 'http://www.inspiredbythis.com/category/wed/real-weddings/'];
        $sources[17] = ['name' => 'Inspired By This: Wedding Fashion', 'url' => 'http://www.inspiredbythis.com/category/wed/wedding-fashion/'];
        $sources[18] = ['name' => 'Inspired By This: Style', 'url' => 'http://www.inspiredbythis.com/category/style/'];
        $sources[19] = ['name' => 'Inspired By This: Entertaining', 'url' => 'http://www.inspiredbythis.com/category/dwell/home-entertaining-dwell/'];
        $sources[20] = ['name' => 'Inspired By This: Decor', 'url' => 'http://www.inspiredbythis.com/category/dwell/home-decor/'];
        $sources[21] = ['name' => 'Inspired By This: Home Tours', 'url' => 'http://www.inspiredbythis.com/category/dwell/home-tours/'];
        $sources[22] = ['name' => 'Inspired By This: Baby Showers', 'url' => 'http://www.inspiredbythis.com/category/grow/baby-showers/'];
        $sources[23] = ['name' => 'Inspired By This: Birthday Parties', 'url' => 'http://www.inspiredbythis.com/category/grow/birthday-parties/'];
        $sources[24] = ['name' => 'Inspired By This: Baby & Kid', 'url' => 'http://www.inspiredbythis.com/category/grow/baby-and-kid/'];
        $sources[25] = ['name' => 'Inspired By This: Motherhood', 'url' => 'http://www.inspiredbythis.com/category/grow/motherhood/'];
        $sources[26] = ['name' => 'Inspired By This: Office Tours', 'url' => 'http://www.inspiredbythis.com/category/business/office-tours/'];
        $sources[27] = ['name' => 'Inspired By This: A Girl Boss', 'url' => 'http://www.inspiredbythis.com/category/business/business-tips/'];
        $sources[28] = ['name' => 'Inspired By This: Office Life', 'url' => 'http://www.inspiredbythis.com/category/business/office-life/'];
        $sources[29] = ['name' => 'Inspired By This: Travel Where To Stay', 'url' => 'http://www.inspiredbythis.com/category/travel-3/travel-destinations/'];
        $sources[30] = ['name' => 'Inspired By This: Travel Where To Eat', 'url' => 'http://www.inspiredbythis.com/category/travel-3/travel-eats/'];
        $sources[31] = ['name' => 'Inspired By This: Wallness', 'url' => 'http://www.inspiredbythis.com/category/wellness/health-tips/'];

        return $sources;
    }

    static protected function sources_contemporist()
    {
        $sources[32] = ['name' => 'Contemporist : Architecture', 'url' => 'https://www.contemporist.com/category/architecture/'];
        $sources[33] = ['name' => 'Contemporist : Interiors', 'url' => 'https://www.contemporist.com/category/interiors/'];
        $sources[34] = ['name' => 'Contemporist : Design', 'url' => 'https://www.contemporist.com/category/design/'];
        $sources[35] = ['name' => 'Contemporist : Art', 'url' => 'https://www.contemporist.com/category/art/'];
        $sources[36] = ['name' => 'Contemporist : Travel', 'url' => 'https://www.contemporist.com/category/travel/'];

        return $sources;
    }

    public function getIdentity()
    {
        return static::FURNITUREIDEAS_US;
    }

}
