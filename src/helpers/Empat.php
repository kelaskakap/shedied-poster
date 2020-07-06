<?php

namespace SheDied\helpers;

use SheDied\helpers\Numbers;
use SheDied\PojokJogjaController;
use SheDied\parser\gadget\laptop\asus\Asus;
use SheDied\parser\gadget\smartphone\GsmArena;
use SheDied\parser\gadget\Brands;

/**
 * technoreview.us
 */
class Empat extends Numbers
{

    const TECHNOREVIEW_US = 'technoreview.us';

    public function fetchPostLinks(PojokJogjaController $controller)
    {

        $doc = $this->fetchLinks($controller->getUrl());
        $postlinks = $controller->getPostLinks();

        if ($this->source_Laptop_ASUS($controller))
        {

            $doc = json_decode($doc);

            if (!empty($doc->Result->Obj))
            {

                foreach ($doc->Result->Obj as $item)
                {

                    $postlinks[] = ["title" => Asus::make_Title(trim($item->PDMarketName)), "link" => Asus::make_URL(trim($item->Url)), 'src' => $controller->getNewsSrc(), 'cat' => $controller->getCategory()];

                    if ($this->enough($postlinks, $controller) && !$controller->auto())
                    {

                        break;
                    }
                }
            }
        }

        if ($this->source_GSMArena($controller))
        {

            \phpQuery::newDocument($doc);

            foreach (pq('div.makers > ul > li a') as $a)
            {

                $link = pq($a)->attr('href');
                $title = pq($a)->elements[0]->nodeValue;
                $postlinks[] = array("title" => GsmArena::make_Title(trim($title)), "link" => GsmArena::make_URL(trim($link)), 'src' => $controller->getNewsSrc(), 'cat' => $controller->getCategory());

                if ($this->enough($postlinks, $controller) && !$controller->auto())
                {

                    break;
                }
            }
        }

        $controller->setPostLinks($postlinks);
    }

    public function switchParsers(PojokJogjaController $controller)
    {

        if ($this->source_Laptop_ASUS($controller))
            $this->parser = Asus::switch_Parser($controller->getNewsSrc());
        elseif ($this->source_GSMArena($controller))
            $this->parser = 'SheDied\parser\gadget\smartphone\GsmArena';
    }

    static public function sources()
    {

        $sources = self::sources_asus();
        $sources += self::sources_gsmarena();

        return $sources;
    }

    protected function source_Laptop_ASUS(PojokJogjaController $controller)
    {

        return $controller->getNewsSrc() >= 1 && $controller->getNewsSrc() < 16;
    }

    protected function source_GSMArena(PojokJogjaController $controller)
    {

        return $controller->getNewsSrc() >= 16 && $controller < 80;
    }

    protected function Laptop_ASUS_Alter_URL(PojokJogjaController $controller)
    {

        if (!$controller->auto())
        {

            $url = $controller->getUrl();

            #addFiltersQueryParams
            $url .= '&Filters=&Sort=3&PageNumber=1&PageSize=20';

            $controller->setUrl($url);
        }
    }

    public function firstRunURL($url, $sourceId, PojokJogjaController $controller)
    {

        parent::firstRunURL($url, $sourceId, $controller);

        $runURL = $url;

        if ($this->source_Laptop_ASUS($controller))
        {

            $page = isset($this->fr[$sourceId]) ? (int) $this->fr[$sourceId] : 20;

            $Query = "&Filters=&Sort=3&PageNumber={$page}&PageSize=20";

            $runURL = $url . $Query;
        } elseif ($this->source_GSMArena($controller))
        {

            $page = isset($this->fr[$sourceId]) ? (int) $this->fr[$sourceId] : 20;

            if ($page > 1)
                $runURL = $this->firstRunURL_GSMArena($url, $page);
        }

        $page--;
        $this->fr[$sourceId] = $page;

        return $runURL;
    }

    protected function firstRunURL_GSMArena($url, $page)
    {

        $url = substr_replace($url, "-0-p{$page}", -4, 0);

        $p = strpos($url, 'phones');
        $insert = '-f';

        return substr_replace($url, 'phones' . $insert, $p, strlen('phones'));
    }

    public function fetchCustomUrls(PojokJogjaController $controller)
    {

        $postlinks = [];

        foreach ($controller->getCustomUrls() as $url)
        {

            if ($this->source_Laptop_ASUS($controller))
            {

                $postlinks[] = ["title" => '', "link" => $url, 'src' => $controller->getNewsSrc(), 'cat' => $controller->getCategory()];
            }
        }

        $controller->setPostLinks($postlinks);
    }

    protected static function sources_asus()
    {

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

    protected static function sources_gsmarena()
    {

        $sources[16] = ['name' => 'Review - GSMArena: Acer', 'url' => 'https://www.gsmarena.com/acer-phones-59.php', 'brand' => Brands::ACER];
        $sources[17] = ['name' => 'Review - GSMArena: Alcatel', 'url' => 'https://www.gsmarena.com/alcatel-phones-5.php', 'brand' => Brands::ALCATEL];
        $sources[18] = ['name' => 'Review - GSMArena: Apple', 'url' => 'https://www.gsmarena.com/apple-phones-48.php', 'brand' => Brands::APPLE];
        $sources[19] = ['name' => 'Review - GSMArena: Blackberry', 'url' => 'https://www.gsmarena.com/blackberry-phones-36.php', 'brand' => Brands::BLACKBERRY];
        $sources[20] = ['name' => 'Review - GSMArena: Ericsson', 'url' => 'https://www.gsmarena.com/ericsson-phones-2.php', 'brand' => Brands::ERICSSON];
        $sources[21] = ['name' => 'Review - GSMArena: Gigabyte', 'url' => 'https://www.gsmarena.com/gigabyte-phones-47.php', 'brand' => Brands::GIGABYTE];
        $sources[22] = ['name' => 'Review - GSMArena: Gionee', 'url' => 'https://www.gsmarena.com/gionee-phones-92.php', 'brand' => Brands::GIONEE];
        $sources[23] = ['name' => 'Review - GSMArena: Google', 'url' => 'https://www.gsmarena.com/google-phones-107.php', 'brand' => Brands::GOOGLE];
        $sources[24] = ['name' => 'Review - GSMArena: Haier', 'url' => 'https://www.gsmarena.com/haier-phones-33.php', 'brand' => Brands::HAIER];
        $sources[25] = ['name' => 'Review - GSMArena: Honor', 'url' => 'https://www.gsmarena.com/honor-phones-121.php', 'brand' => Brands::HONOR];
        $sources[26] = ['name' => 'Review - GSMArena: HP', 'url' => 'https://www.gsmarena.com/hp-phones-41.php', 'brand' => Brands::HP];
        $sources[27] = ['name' => 'Review - GSMArena: HTC', 'url' => 'https://www.gsmarena.com/htc-phones-45.php', 'brand' => Brands::HTC];
        $sources[28] = ['name' => 'Review - GSMArena: Huawei', 'url' => 'https://www.gsmarena.com/huawei-phones-58.php', 'brand' => Brands::HUAWEI];
        $sources[29] = ['name' => 'Review - GSMArena: I-Mate', 'url' => 'https://www.gsmarena.com/i_mate-phones-35.php', 'brand' => Brands::IMATE];
        $sources[30] = ['name' => 'Review - GSMArena: I-Mobile', 'url' => 'https://www.gsmarena.com/i_mobile-phones-52.php', 'brand' => Brands::IMOBILE];
        $sources[31] = ['name' => 'Review - GSMArena: Icemoblie', 'url' => 'https://www.gsmarena.com/icemobile-phones-69.php', 'brand' => Brands::ICEMOBILE];
        $sources[32] = ['name' => 'Review - GSMArena: Infinix', 'url' => 'https://www.gsmarena.com/infinix-phones-119.php', 'brand' => Brands::INFINIX];
        $sources[33] = ['name' => 'Review - GSMArena: Karbonn', 'url' => 'https://www.gsmarena.com/karbonn-phones-83.php', 'brand' => Brands::KARBONN];
        $sources[34] = ['name' => 'Review - GSMArena: Lava', 'url' => 'https://www.gsmarena.com/lava-phones-94.php', 'brand' => Brands::LAVA];
        $sources[35] = ['name' => 'Review - GSMArena: Lenovo', 'url' => 'https://www.gsmarena.com/lenovo-phones-73.php', 'brand' => Brands::LENOVO];
        $sources[36] = ['name' => 'Review - GSMArena: LG', 'url' => 'https://www.gsmarena.com/lg-phones-20.php', 'brand' => Brands::LG];
        $sources[37] = ['name' => 'Review - GSMArena: Maxon', 'url' => 'https://www.gsmarena.com/maxon-phones-14.php', 'brand' => Brands::MAXON];
        $sources[38] = ['name' => 'Review - GSMArena: Maxwest', 'url' => 'https://www.gsmarena.com/maxwest-phones-87.php', 'brand' => Brands::MAXWEST];
        $sources[39] = ['name' => 'Review - GSMArena: Meizu', 'url' => 'https://www.gsmarena.com/meizu-phones-74.php', 'brand' => Brands::MEIZU];
        $sources[40] = ['name' => 'Review - GSMArena: Micromax', 'url' => 'https://www.gsmarena.com/micromax-phones-66.php', 'brand' => Brands::MICROMAX];
        $sources[41] = ['name' => 'Review - GSMArena: Microsoft', 'url' => 'https://www.gsmarena.com/microsoft-phones-64.php', 'brand' => Brands::MICROSOFT];
        $sources[42] = ['name' => 'Review - GSMArena: Motorola', 'url' => 'https://www.gsmarena.com/motorola-phones-4.php', 'brand' => Brands::MOTOROLA];
        $sources[43] = ['name' => 'Review - GSMArena: NEC', 'url' => 'https://www.gsmarena.com/nec-phones-12.php', 'brand' => Brands::NEC];
        $sources[44] = ['name' => 'Review - GSMArena: Nokia', 'url' => 'https://www.gsmarena.com/nokia-phones-1.php', 'brand' => Brands::NOKIA];
        $sources[45] = ['name' => 'Review - GSMArena: O2', 'url' => 'https://www.gsmarena.com/o2-phones-30.php', 'brand' => Brands::O2];
        $sources[46] = ['name' => 'Review - GSMArena: Oppo', 'url' => 'https://www.gsmarena.com/oppo-phones-82.php', 'brand' => Brands::OPPO];
        $sources[47] = ['name' => 'Review - GSMArena: Panasonic', 'url' => 'https://www.gsmarena.com/panasonic-phones-6.php', 'brand' => Brands::PANASONIC];
        $sources[48] = ['name' => 'Review - GSMArena: Pantech', 'url' => 'https://www.gsmarena.com/pantech-phones-32.php', 'brand' => Brands::PANTECH];
        $sources[49] = ['name' => 'Review - GSMArena: Philips', 'url' => 'https://www.gsmarena.com/philips-phones-11.php', 'brand' => Brands::PHILIPS];
        $sources[50] = ['name' => 'Review - GSMArena: Plum', 'url' => 'https://www.gsmarena.com/plum-phones-72.php', 'brand' => Brands::PLUM];
        $sources[51] = ['name' => 'Review - GSMArena: Prestigo', 'url' => 'https://www.gsmarena.com/prestigio-phones-86.php', 'brand' => Brands::PRESTIGO];
        $sources[52] = ['name' => 'Review - GSMArena: Qmobile', 'url' => 'https://www.gsmarena.com/qmobile-phones-103.php', 'brand' => Brands::QMOBILE];
        $sources[53] = ['name' => 'Review - GSMArena: Realme', 'url' => 'https://www.gsmarena.com/realme-phones-118.php', 'brand' => Brands::REALME];
        $sources[54] = ['name' => 'Review - GSMArena: Sagem', 'url' => 'https://www.gsmarena.com/sagem-phones-13.php', 'brand' => Brands::SAGEM];
        $sources[55] = ['name' => 'Review - GSMArena: Samsung', 'url' => 'https://www.gsmarena.com/samsung-phones-9.php', 'brand' => Brands::SAMSUNG];
        $sources[56] = ['name' => 'Review - GSMArena: Sendo', 'url' => 'https://www.gsmarena.com/sendo-phones-18.php', 'brand' => Brands::SENDO];
        $sources[57] = ['name' => 'Review - GSMArena: Sewon', 'url' => 'https://www.gsmarena.com/sewon-phones-26.php', 'brand' => Brands::SEWON];
        $sources[58] = ['name' => 'Review - GSMArena: Sharp', 'url' => 'https://www.gsmarena.com/sharp-phones-23.php', 'brand' => Brands::SHARP];
        $sources[59] = ['name' => 'Review - GSMArena: Sony', 'url' => 'https://www.gsmarena.com/sony-phones-7.php', 'brand' => Brands::SONY];
        $sources[60] = ['name' => 'Review - GSMArena: Sony Ericsson', 'url' => 'https://www.gsmarena.com/sony_ericsson-phones-19.php', 'brand' => Brands::SONY_ERICSSON];
        $sources[61] = ['name' => 'Review - GSMArena: Spice', 'url' => 'https://www.gsmarena.com/spice-phones-68.php', 'brand' => Brands::SPICE];
        $sources[62] = ['name' => 'Review - GSMArena: T-mobile', 'url' => 'https://www.gsmarena.com/t_mobile-phones-55.php', 'brand' => Brands::TMOBILE];
        $sources[63] = ['name' => 'Review - GSMArena: Tecno', 'url' => 'https://www.gsmarena.com/tecno-phones-120.php', 'brand' => Brands::TECNO];
        $sources[64] = ['name' => 'Review - GSMArena: Telit', 'url' => 'https://www.gsmarena.com/telit-phones-16.php', 'brand' => Brands::TELIT];
        $sources[65] = ['name' => 'Review - GSMArena: Toshiba', 'url' => 'https://www.gsmarena.com/toshiba-phones-44.php', 'brand' => Brands::TOSHIBA];
        $sources[66] = ['name' => 'Review - GSMArena: Unnecto', 'url' => 'https://www.gsmarena.com/unnecto-phones-91.php', 'brand' => Brands::UNNECTO];
        $sources[67] = ['name' => 'Review - GSMArena: Vertu', 'url' => 'https://www.gsmarena.com/vertu-phones-39.php', 'brand' => Brands::VERTU];
        $sources[68] = ['name' => 'Review - GSMArena: Verykool', 'url' => 'https://www.gsmarena.com/verykool-phones-70.php', 'brand' => Brands::VERYKOOL];
        $sources[69] = ['name' => 'Review - GSMArena: Vivo', 'url' => 'https://www.gsmarena.com/vivo-phones-98.php', 'brand' => Brands::VIVO];
        $sources[70] = ['name' => 'Review - GSMArena: VK Mobile', 'url' => 'https://www.gsmarena.com/vk_mobile-phones-37.php', 'brand' => Brands::VK_MOBILE];
        $sources[71] = ['name' => 'Review - GSMArena: Vodafone', 'url' => 'https://www.gsmarena.com/vodafone-phones-53.php', 'brand' => Brands::VODAFONE];
        $sources[72] = ['name' => 'Review - GSMArena: Wiko', 'url' => 'https://www.gsmarena.com/wiko-phones-96.php', 'brand' => Brands::WIKO];
        $sources[73] = ['name' => 'Review - GSMArena: Xiaomi', 'url' => 'https://www.gsmarena.com/xiaomi-phones-80.php', 'brand' => Brands::XIAOMI];
        $sources[74] = ['name' => 'Review - GSMArena: XOLO', 'url' => 'https://www.gsmarena.com/xolo-phones-85.php', 'brand' => Brands::XOLO];
        $sources[75] = ['name' => 'Review - GSMArena: Yezz', 'url' => 'https://www.gsmarena.com/yezz-phones-78.php', 'brand' => Brands::YEZZ];
        $sources[76] = ['name' => 'Review - GSMArena: ZTE', 'url' => 'https://www.gsmarena.com/zte-phones-62.php', 'brand' => Brands::ZTE];
        $sources[77] = ['name' => 'Review - GSMArena: Celkon', 'url' => 'https://www.gsmarena.com/celkon-phones-75.php', 'brand' => Brands::CELKON];
        $sources[78] = ['name' => 'Review - GSMArena: Siemens', 'url' => 'https://www.gsmarena.com/siemens-phones-3.php', 'brand' => Brands::SIEMENS];
        $sources[79] = ['name' => 'Review - GSMArena: Asus', 'url' => 'https://www.gsmarena.com/asus-phones-46.php', 'brand' => Brands::ASUS];

        return $sources;
    }

    public function scanURL(PojokJogjaController $controller, $params = array())
    {
        if ($this->source_Laptop_ASUS($controller))
        {

            $this->Laptop_ASUS_Alter_URL($controller);
        }
    }

    public function getIdentity()
    {
        return static::TECHNOREVIEW_US;
    }

}
