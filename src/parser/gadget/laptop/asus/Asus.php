<?php

namespace SheDied\parser\gadget\laptop\asus;

use SheDied\parser\gadget\laptop\Laptop;
use SheDied\parser\gadget\Brands;

abstract class Asus extends Laptop {

    protected $spec_link;
    protected $support_link;
    protected $gallery_link;

    const SITE_ADDR = 'https://www.asus.com';

    public function __construct() {

        parent::__construct();
        $this->setBrand(Brands::ASUS);
    }

    static public function make_Title($title) {

        return $title . ' Reviews Specs Price and Drivers';
    }

    static public function make_URL($url) {

        return 'https:' . $url;
    }

    /**
     * Based on SheDied\helpers\Empat::sources()
     * @param mixed $param array index
     * @return string Parser
     */
    static public function switch_Parser($param) {

        switch ((int) $param) {

            case 1:
                $parser = 'SheDied\parser\gadget\laptop\asus\ZenBook';
                break;
            case 2:
                $parser = 'SheDied\parser\gadget\laptop\asus\ZenBookPro';
                break;
            case 3:
                $parser = 'SheDied\parser\gadget\laptop\asus\ZenBookS';
                break;
            case 4:
                $parser = 'SheDied\parser\gadget\laptop\asus\ZenBookClassic';
                break;
            case 5:
                $parser = 'SheDied\parser\gadget\laptop\asus\VivoBook';
                break;
            case 6:
                $parser = 'SheDied\parser\gadget\laptop\asus\VivoBookPro';
                break;
            case 7:
                $parser = 'SheDied\parser\gadget\laptop\asus\VivoBookS';
                break;
            case 8:
                $parser = 'SheDied\parser\gadget\laptop\asus\VivoBookClassic';
                break;
            case 9:
                $parser = 'SheDied\parser\gadget\laptop\asus\StudioBook';
                break;
            case 10:
                $parser = 'SheDied\parser\gadget\laptop\asus\AsusLaptop';
                break;
            default :
                $parser = '';
                break;
        }

        return $parser;
    }

    protected function do_CURL($url) {

        $ch = curl_init();
        $parse = parse_url($url);
        $host = $parse['host'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: $host"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36');
        curl_setopt($ch, CURLOPT_HEADER, false);
        $output = curl_exec($ch);

        $error = curl_error($ch);

        if ($error) {
            throw new \Exception($error);
        }

        curl_close($ch);

        return $output;
    }

    protected function _getFeaturedImage() {

        $img = pq('meta[property="og:image"]')->attr('content');
        $this->featured_image = trim($img);
    }

    /**
     * Get Links of related page of Product
     * Specs, gallery, support
     */
    protected function dom_Links() {

        $node = pq('div#overview-top-nav');

        $support_link = trim($node->find('li#lisupport > a')->attr('href'));

        if (!$support_link)
            $support_link = $this->url . "HelpDesk";
        else
            $support_link = self::SITE_ADDR . $support_link;

        $this->spec_link = self::SITE_ADDR . trim($node->find('li#lispecifications > a')->attr('href'));
        $this->support_link = $support_link;
        $this->gallery_link = self::SITE_ADDR . trim($node->find('li#ligallery > a')->attr('href'));
    }

    protected function dom_Gallery() {

        $node = pq('ul.gallery-list');

        foreach ($node->find('img') as $img) {

            $this->photos[] = Asus::SITE_ADDR . trim(pq($img)->attr('src'));
        }

        if (!$this->photos)
            $this->logError([
                'source' => $this->gallery_link,
                'message' => 'new pattern?'
            ]);
    }

    protected function try_Content_1() {

        $node = pq('div#AWD');

        if (!$node->size()) {
            $node = pq('div#CMD');
        }

        if (!$node->size()) {
            $node = pq('div#wrapper');
        }

        $text = "";
        $st_s = "div.content__title, div.desc";
        $si_s = "div.content__info, p.content-p:first";

        foreach ($node->find('section') as $section) {

            $st = pq($section)->find($st_s);
            $si = pq($section)->find($si_s);

            if ($st->count() < 2) {

                $sub_title = trim($st->text());
                $sub_info = trim($si->text());
                $text .= "<h2 class=\"sell-point\">{$sub_title}</h2><p>{$sub_info}</p>";
            }
        }

        $this->content = $text;
    }

    protected function try_Content_2() {

        $node = pq('div#box-productOverview-containter');
        $text = "";
        $st_s = 'div.slogan, div.subT-big, div.box-word-cent > div.subT';
        $si_s = 'div.box-intro, div.txt-cent-mid, div.txt-left-mid, div.txt-cent, div.txt-cent-big';

        foreach ($node->find('section') as $sec) {

            $st = pq($sec)->find($st_s);
            $si = pq($sec)->find($si_s);

            if ($st->count() < 2) {

                $sub_title = trim($st->text());
                $sub_info = trim($si->text());
                $text .= "<h2 class=\"sell-point\">{$sub_title}</h2><p>{$sub_info}</p>";
            }
        }

        $this->content = $text;
    }

    protected function try_Content_3() {

        $node = pq('div#Features');
        $text = "";
        $st_s = "div.desc";
        $si_s = "div.intro > p";

        foreach ($node->find('section') as $section) {

            $st = pq($section)->find($st_s);
            $si = pq($section)->find($si_s);

            if ($st->count() < 2) {

                $sub_title = trim($st->text());
                $sub_info = trim($si->text());
                $text .= "<h2 class=\"sell-point\">{$sub_title}</h2><p>{$sub_info}</p>";
            }
        }

        $this->content = $text;
    }

    protected function dom_Support() {

        $info = "<p>Get more detailed information such as Driver Utility, FAQ, Manual & Document, Warranty Information of {$this->model}.</p>";
        $info .= "<p>";
        $info .= "<a class='btn btn-primary' href='{$this->support_link}' target='_blank' rel='noopener nofollow'>Go To Page {$this->model} Support</a>";
        $info .= "</p>";

        return $info;
    }

    protected function try_Specs_1() {

        $node = pq('div#spec-area');

        foreach ($node->find('li') as $li) {

            $label = trim(pq($li)->find('span.spec-item')->text());
            $temp = pq($li)->find('div.spec-data');
            pq($temp)->find('*')->removeAttr('*');
            $value = trim(pq($temp)->html());

            if ($label && $value)
                $this->specs[$label] = $value;
        }
    }

    protected function try_Specs_2() {

        $node = pq('div.insoweTable');

        foreach ($node->find('section') as $section) {

            pq($section)->find('strong')->after('<br>')->before('<br>');
            $label = trim(pq($section)->find('div.insoweCol1')->text());
            $value = trim(pq($section)->find('div.insoweCol2')->find('p')->removeAttr('*')->html());

            if ($label && $value)
                $this->specs[$label] = $value;
        }
    }

    protected function try_Specs_3() {

        $node = pq('div.spec__container');

        foreach ($node->find('div.spec__content') as $div) {

            $label = trim(pq($div)->find('div.spec-key')->text());
            $temp = pq($div)->find('div.item-right');
            pq($temp)->find('*')->removeAttr('*');
            $value = trim(pq($temp)->html());

            if ($label && $value)
                $this->specs[$label] = $value;
        }
    }

    protected function dom_Model() {

        return pq('#ctl00_ContentPlaceHolder1_ctl00_span_model_name')->text();
    }

    protected function dom_Content() {

        $this->try_Content_1();

        if (!$this->content)
            $this->try_Content_2();

        if (!$this->content)
            $this->try_Content_3();
        
        if (!$this->content)
            $this->try_Content_4 ();

        if (!$this->content)
            $this->logError([
                'source' => $this->url,
                'message' => 'new pattern?'
            ]);

        $this->closingContent();
    }

    protected function closingContent() {

        $text = "<p class=\"gotoofficial\">For more detailed information about {$this->model}, you can visit the official page of this product.<br>Go to {$this->model} <a href=\"{$this->url}\" target=\"_blank\" rel=\"nofollow\">official page</a>.</p>";
        $this->content .= $text;
    }

    protected function dom_Specs() {

        $this->try_Specs_1();

        if (!$this->specs)
            $this->try_Specs_2();

        if (!$this->specs)
            $this->try_Specs_3();

        if (!$this->specs)
            $this->logError([
                'source' => $this->spec_link,
                'message' => 'new pattern?'
            ]);
    }

    protected function try_Content_4() {

        $node = pq('div#wrap');

        $text = "";
        $st_s = "article.text h1";
        $si_s = "article.text p";

        foreach ($node->find('section') as $section) {

            $st = pq($section)->find($st_s);
            $si = pq($section)->find($si_s);

            if ($st->count() < 2) {

                $sub_title = trim($st->text());
                $sub_info = trim($si->text());
                $text .= "<h2 class=\"sell-point\">{$sub_title}</h2><p>{$sub_info}</p>";
            }
        }

        $this->content = $text;
    }

}
