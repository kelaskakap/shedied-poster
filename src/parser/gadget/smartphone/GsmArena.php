<?php

namespace SheDied\parser\gadget\smartphone;

use SheDied\SheDieDConfig;

class GsmArena extends Smartphone {

    const GSMARENA_ADDR = 'https://www.gsmarena.com';

    protected $gallery_link;
    protected $review_link;

    public function __construct() {

        parent::__construct();
    }

    protected function getPostDetail() {

        $doc = $this->curlGrabContent();
        $html = $this->make_DOM($doc);

        $model = $this->dom_Model();
        $this->setModel(trim($model));

        //url utama produk gsm arena
        //adalah spek gadget
        $this->dom_Specs();
        //get photo
        $this->_getFeaturedImage();

        //banyak detil menarik untuk jadi bahan tags
        $this->dom_Tags();

        $this->dom_Links();
        $this->setProductLink($this->url);

        if (!$this->review_link) {

            $this->dom_Content_render();
        } else {

            //get review if any
            $doc = $this->do_CURL($this->review_link);
            $html = $this->make_DOM($doc);

            $this->dom_Content();
        }

        if ($this->gallery_link) {

            $doc = $this->do_CURL($this->gallery_link);
            $html = $this->make_DOM($doc);

            $this->dom_Gallery();
            //replace dari galeri
            //foto lebih bagus
            //nuts
            $this->_getFeaturedImage();
        }

        $support = $this->dom_Support();
        $this->setProductSupport($support);
    }

    protected function dom_Content() {

        $node = pq('div#review-body');
        \phpQuery::each(pq($node)->children(), function($index, $element) {
            if (!pq($element)->is('img'))
                pq($element)->removeAttr('*');
            else
                pq($element)->removeAttr('class')
                        ->removeAttr('height')
                        ->removeAttr('width')
                        ->wrap('<p class="text-center">');
            if (pq($element)->is('h3'))
                pq($element)->wrap('<h2>')->contentsUnwrap();
        });

        pq($node)->find('iframe')->parent('p')->addClass('text-center');

        $this->content = $node->html();

        if (!$this->content)
            $this->logError([
                'source' => $this->review_link,
                'message' => 'new pattern?'
            ]);

        $this->closingContent();
    }

    protected function closingContent() {

        $text = "<p class=\"gotoofficial\">For more detailed information about {$this->model}, check out the {$this->model} <a href=\"{$this->review_link}\" target=\"_blank\" rel=\"nofollow\">reviews page</a>.</p>";
        $this->content .= $text;
    }

    protected function dom_Gallery() {

        $node = pq('div#pictures-list');

        foreach ($node->find('img') as $img) {

            $src = pq($img)->attr('src');
            $this->photos[] = trim($src);
        }

        if (!$this->photos)
            $this->logError([
                'source' => $this->gallery_link,
                'message' => 'new pattern?'
            ]);
    }

    protected function dom_Model() {

        return pq('h1.specs-phone-name-title')->text();
    }

    protected function dom_Links() {

        foreach (pq('li.article-info-meta-link') as $li) {

            if (!$this->gallery_link && trim(pq($li)->text()) == 'Pictures')
                $this->gallery_link = self::GSMARENA_ADDR . '/' . trim(pq($li)->find('a')->attr('href'));

            if (!$this->review_link && trim(pq($li)->text()) == 'Review')
                $this->review_link = self::GSMARENA_ADDR . '/' . trim(pq($li)->find('a')->attr('href'));
        }
    }

    protected function dom_Support() {

        $info = "<p>There no support yet available for the {$this->model} model.</p>";
        return $info;
    }

    protected function dom_Specs() {

        $node = pq('div#specs-list');

        foreach ($node->find('table') as $table) {

            $th_text = '';
            foreach (pq($table)->find('tr') as $tr) {

                $th = pq($tr)->find('th');
                $ttl = pq($tr)->find('td.ttl');
                $nfo = pq($tr)->find('td.nfo');

                if ($th->size() > 0)
                    $th_text = trim($th->text());

                $ttl_text = trim($ttl->text());
                $nfo_text = trim($nfo->text());

                $this->specs[$th_text][$ttl_text] = $nfo_text;
            }
        }

        if (!$this->specs)
            $this->logError([
                'source' => $this->spec_link,
                'message' => 'new pattern?'
            ]);
    }

    static public function make_Title($title) {

        return $title . ' Reviews Specs and Price';
    }

    static public function make_URL($url) {

        return self::GSMARENA_ADDR . '/' . $url;
    }

    public function grab() {

        $brand = SheDieDConfig::getSource($this->getSourceCategory())['brand'];
        $this->setBrand($brand);
        $this->getPostDetail();
        $this->aggregateContent();
        $this->generateSeoMetaDescription();
        $this->generateSeoMetaKeywords();
        $this->generateSeoMetaTitle();
    }

    protected function dom_Tags() {

        $node = pq('div.specs-accent');

        foreach ($node->find('span.specs-brief-accent') as $s1) {

            $this->tags[] = trim(pq($s1)->text());
        }

        foreach ($node->find('li.help.accented') as $li) {

            $accent = pq($li)->find('strong')->text();
            $hl = pq($li)->find('div')->text();

            $this->tags[] = trim($accent);
            $this->tags[] = trim($hl);
        }
    }

    protected function _getFeaturedImage() {

        $photo = pq('div.specs-photo-main img')->attr('src');
        $this->featured_image = trim($photo);

        if ($this->photos)
            $this->featured_image = $this->photos[0];
    }

    public function specsTable() {

        $html = "<table class='table gadget-specs'>";
        $html .= "<tbody>";

        foreach ($this->specs as $feature => $list) {

            $html .= "<tr>";
            $html .= "<th colspan='2'>";
            $html .= $feature;
            $html .= "</th>";
            $html .= "</tr>";

            foreach ($list as $label => $value) {

                $html .= "<tr>";
                $html .= "<td>{$label}</td>";
                $html .= "<td>{$value}</td>";
                $html .= "</tr>";
            }
        }

        $html .= "</tbody>";
        $html .= "</table>";

        return $html;
    }

    protected function dom_Content_render() {

        $released = trim(pq('span[data-spec="released-hl"]')->text());
        $screensize = trim(pq('span[data-spec="displaysize-hl"]')->text());
        $campix = trim(pq('span[data-spec="camerapixels-hl"]')->text());
        $dimenssion = trim(pq('td[data-spec="dimensions"]')->text());
        $weight = trim(pq('span[data-spec="body-hl"]')->text());
        $color = trim(pq('td[data-spec="colors"]')->text());
        $internal = trim(pq('span[data-spec="storage-hl"]')->text());
        $os = trim(pq('span[data-spec="os-hl"]')->text());
        $chipset = trim(pq('td[data-spec="chipset"]')->text());
        $net2g = trim(pq('td[data-spec="net2g"]')->text());
        $net3g = trim(pq('td[data-spec="net3g"]')->text());
        $net4g = trim(pq('td[data-spec="net4g"]')->text());
        $speed = trim(pq('td[data-spec="speed"]')->text());

        $netwk = $net2g . ' | ' . $net3g . ' | ' . $net4g;

        $text = "<p>The {$this->model} has caused a lot of media hype since {$released}. This powerhouse of a Smartphone boasts industry leading features such as the {$screensize} touch screen and an impressive {$campix} mega pixel digital camera.</p>";

        $text .= "<p>Given the fact that this handset boasts a large touchscreen, this is reflected by its dimensions measuring {$dimenssion}. This is sure to be seen as a bonus in the eyes of some users who prefer a larger phone, and it only weighs {$weight} making it pocket friendly handset. The {$this->model} is available in {$color} colour variants so it offers appeal to fashion conscious users.</p>";

        $text .= "<p> An impressive {$internal} of internal storage is provided as standard within the {$this->model}. Although this should suffice for the needs of most users, this can be upgraded with a bigger card offering the potential to store an entire digital music collection or a large number of video files etc. The vast internal phonebook is capable of storing an impressive number of contacts and also includes the Photocall feature.</p>";

        $text .= "<p>One of the flagship features of this model is the stunning {$campix} digital camera. As well as its high pixel resolution, it boasts a host of features which aimed to include the quality of the photos as well as simplifying the task of taking them. These range from auto focus, touch focus, image stabilisation, face & smile detection and an LED flash, meaning low lighting conditions are no obstacle. The handy geo-tagging facility is also included which, based on GPS, records the location at which a photo was taken. Of course the camera can also shoot video footage and this is in WVGA quality at 30 frames per second.</p>";

        $text .= "<p>The increasingly popular Android operating system, in this case {$os} is the operating system of choice within the {$this->model}. Alongside a powerful {$chipset} processor, these to power houses offer higher performance and handle demanding applications with ease whilst providing lightning fast reactions of the user interface. In terms of entertainment features, there is a versatile internal media player along with a number of pre-installed games. Social networking fans are well catered for with dedicated Facebook and twitter applications which offer integration to these popular websites so users can stay up to date with friends and family with a single touch of the screen. GPS with A-GPS support provides the ability to supports applications such as geo-tagging and a digital compass as well as Google maps.</p>";

        $text .= "<p>Thanks to the employment of a number of connectivity features, it is never been more convenient to surf the Internet is appears with the {$this->model}. {$netwk} coverage provides the primary method of Internet connectivity courtesy of an HSDPA/LTE connection at speeds of up to {$speed}. Wi-Fi provides the fastest Internet connection when available by utilising the signals provided by wireless Internet routers.</p>";

        $text .= "<p>It is easy to see why the {$this->model} has proven to be such a popular handset. The versatility of the {$os} combined with its aesthetic appeal and broad functionality means its future success looks assured.</p>";

        $this->content = $text;
        $this->closingContent();
    }

}
