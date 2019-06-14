<?php

namespace SheDied\helpers;

use SheDied\helpers\Numbers;
use SheDied\PojokJogjaController;
use SheDied\parser\gadget\laptop\asus\Asus;

/**
 * technoreview.us
 */
class Empat extends Numbers {

    const TECHNOREVIEW_US = 'technoreview.us';

    public function fetchPostLinks(PojokJogjaController $controller) {

        if ($this->source_Laptop_ASUS($controller)) {

            $this->Laptop_ASUS_Alter_URL($controller);
        }

        $doc = $this->fetchLinks($controller->getUrl());

        # dipindah untuk parser lain ya. ASUS gak butuh
        # karena lewat API
        //\phpQuery::newDocument($doc);

        $postlinks = $controller->getPostLinks();

        if ($this->source_Laptop_ASUS($controller)) {

            $doc = json_decode($doc);

            if (!empty($doc->Result->Obj)) {

                foreach ($doc->Result->Obj as $item) {

                    $postlinks[] = ["title" => Asus::make_Title(trim($item->PDMarketName)), "link" => Asus::make_URL(trim($item->Url)), 'src' => $controller->getNewsSrc(), 'cat' => $controller->getCategory()];

                    if ($this->enough($postlinks, $controller) && !$controller->auto()) {

                        break;
                    }
                }
            }
        }

        $controller->setPostLinks($postlinks);
    }

    public function switchParsers(PojokJogjaController $controller) {

        if ($this->source_Laptop_ASUS($controller))
            $this->parser = Asus::switch_Parser($controller->getNewsSrc());
    }

    static public function sources() {

        #asus
        $sources[1] = ['name' => 'Review - Asus: ZenBook Series', 'url' => 'https://www.asus.com/OfficialSiteAPI.asmx/GetModelResults?WebsiteId=1&ProductLevel2Id=155&FiltersCategory=35305'];
        $sources[2] = ['name' => 'Review - Asus: ZenBook Pro Series', 'url' => 'https://www.asus.com/OfficialSiteAPI.asmx/GetModelResults?WebsiteId=1&ProductLevel2Id=155&FiltersCategory=26718'];
        $sources[3] = ['name' => 'Review - Asus: ZenBook S Series', 'url' => 'https://www.asus.com/OfficialSiteAPI.asmx/GetModelResults?WebsiteId=1&ProductLevel2Id=155&FiltersCategory=27677'];
        $sources[4] = ['name' => 'Review - Asus: ZenBook Classic Series', 'url' => 'https://www.asus.com/OfficialSiteAPI.asmx/GetModelResults?WebsiteId=1&ProductLevel2Id=155&FiltersCategory=5916'];
        $sources[5] = ['name' => 'Review - Asus: VivoBook Series', 'url' => 'https://www.asus.com/OfficialSiteAPI.asmx/GetModelResults?WebsiteId=1&ProductLevel2Id=155&FiltersCategory=35213'];
        $sources[6] = ['name' => 'Review - Asus: VivoBook Pro Series', 'url' => 'https://www.asus.com/OfficialSiteAPI.asmx/GetModelResults?WebsiteId=1&ProductLevel2Id=155&FiltersCategory=6070'];
        $sources[7] = ['name' => 'Review - Asus: VivoBook S Series', 'url' => 'https://www.asus.com/OfficialSiteAPI.asmx/GetModelResults?WebsiteId=1&ProductLevel2Id=155&FiltersCategory=27337'];
        $sources[8] = ['name' => 'Review - Asus: VivoBook Classic Series', 'url' => 'https://www.asus.com/OfficialSiteAPI.asmx/GetModelResults?WebsiteId=1&ProductLevel2Id=155&FiltersCategory=14309'];
        $sources[9] = ['name' => 'Review - Asus: StudioBook Series', 'url' => 'https://www.asus.com/OfficialSiteAPI.asmx/GetModelResults?WebsiteId=1&ProductLevel2Id=155&FiltersCategory=35489'];
        $sources[10] = ['name' => 'Review - Asus: ASUS Laptop Series', 'url' => 'https://www.asus.com/OfficialSiteAPI.asmx/GetModelResults?WebsiteId=1&ProductLevel2Id=155&FiltersCategory=27762'];
        $sources[11] = ['name' => 'Review - Asus: Chromebook Series', 'url' => 'https://www.asus.com/OfficialSiteAPI.asmx/GetModelResults?WebsiteId=1&ProductLevel2Id=155&FiltersCategory=6301'];
        $sources[12] = ['name' => 'Review - Asus: ASUSPRO Series', 'url' => 'https://www.asus.com/OfficialSiteAPI.asmx/GetModelResults?WebsiteId=1&ProductLevel2Id=155&FiltersCategory=14232'];
        $sources[13] = ['name' => 'Review - Asus: Gaming Series', 'url' => 'https://www.asus.com/OfficialSiteAPI.asmx/GetModelResults?WebsiteId=1&ProductLevel2Id=155&FiltersCategory=5993'];
        $sources[14] = ['name' => 'Review - Asus: FX/ZX Series', 'url' => 'https://www.asus.com/OfficialSiteAPI.asmx/GetModelResults?WebsiteId=1&ProductLevel2Id=155&FiltersCategory=17067'];
        $sources[15] = ['name' => 'Review - Asus: ASUS TUF Gaming Series', 'url' => 'https://www.asus.com/OfficialSiteAPI.asmx/GetModelResults?WebsiteId=1&ProductLevel2Id=155&FiltersCategory=32330'];

        return $sources;
    }

    protected function source_Laptop_ASUS(PojokJogjaController $controller) {

        return $controller->getNewsSrc() >= 1 && $controller->getNewsSrc() < 16;
    }

    protected function Laptop_ASUS_Alter_URL(PojokJogjaController $controller) {

        if (!$controller->auto()) {

            $url = $controller->getUrl();

            #addFiltersQueryParams
            $url .= '&Filters=&Sort=3&PageNumber=1&PageSize=20';

            $controller->setUrl($url);
        }
    }

    public function firstRunURL($url, $sourceId) {

        parent::firstRunURL($url, $sourceId);

        $page = isset($this->fr[$sourceId]) ? (int) $this->fr[$sourceId] : 20;

        $Query = "&Filters=&Sort=3&PageNumber={$page}&PageSize=20";

        $page--;
        $this->fr[$sourceId] = $page;

        return $url . $Query;
    }

    public function fetchCustomUrls(PojokJogjaController $controller) {

        $postlinks = [];

        foreach ($controller->getCustomUrls() as $url) {

            if ($this->source_Laptop_ASUS($controller)) {

                $postlinks[] = ["title" => '', "link" => $url, 'src' => $controller->getNewsSrc(), 'cat' => $controller->getCategory()];
            }
        }

        $controller->setPostLinks($postlinks);
    }

}
