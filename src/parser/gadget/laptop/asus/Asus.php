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

    /**
     * Get Links of related page of Product
     * Specs, gallery, support
     */
    protected function dom_Links() {

        $node = pq('div#overview-top-nav');
        $this->spec_link = self::SITE_ADDR . trim($node->find('li#lispecifications > a')->attr('href'));
        $this->support_link = self::SITE_ADDR . trim($node->find('li#lisupport > a')->attr('href'));
        $this->gallery_link = self::SITE_ADDR . trim($node->find('li#ligallery > a')->attr('href'));
    }

    protected function dom_Gallery() {

        $node = pq('ul.gallery-list');

        foreach ($node->find('img') as $img) {

            $this->photos[] = Asus::SITE_ADDR . trim(pq($img)->attr('src'));
        }
    }

}
