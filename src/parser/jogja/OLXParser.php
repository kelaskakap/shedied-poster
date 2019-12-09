<?php

namespace SheDied\parser\jogja;

use SheDied\parser\AbstractParser;

class OLXParser extends AbstractParser {

    const OLX_ADDR = 'https://www.olx.co.id';

    protected $specs = [];
    protected $desc;
    public $price;
    public $seller = [];
    protected $photos = [];
    public $tayang;
    protected $start;
    protected $end;

    protected function getPostDetail() {

        $doc = $this->curlGrabContent();
        $html = $this->make_DOM($doc);
    }

    static public function make_URL($url) {

        return self::OLX_ADDR . $url;
    }

    public function grab() {

        $this->getPostDetail();
        $this->specs();
        $this->price();
        $this->_getFeaturedImage();
        $this->desc();
        $this->seller();
        $this->tayang();
        $this->photos();
        $this->_getTags();
        $this->generateSeoMetaDescription();
        $this->generateSeoMetaTitle();
        $this->renderHtml();
    }

    protected function _getFeaturedImage() {

        $this->featured_image = pq('meta[property="og:image"]')->attr('content');
    }

    protected function specs() {

        foreach (pq('div._3_knn') as $div) {

            $label = pq($div)->find('span._25oXN')->text();
            $value = pq($div)->find('span._2vNpt')->text();

            $this->specs[trim($label)] = trim($value);
        }
    }

    protected function desc() {

        $desc = pq('div[data-aut-id="itemDescriptionContent"]');

        \phpQuery::each(pq($desc)->children(), function($index, $element) {
            pq($element)->removeAttr('*');
        });

        $this->desc = trim($desc->html());
    }

    protected function price() {

        $this->price = trim(pq('span[data-aut-id="itemPrice"]')->text());
    }

    protected function seller() {

        $x = pq('div._224W6 a');
        $link = pq($x)->attr('href');
        $name = pq($x)->find('div._3oOe9')->text();

        $this->seller['name'] = trim($name);
        $this->seller['link'] = self::OLX_ADDR . trim($link);
    }

    protected function photos() {

        $hell = pq('div._2f8d4');

        foreach (pq($hell)->find('button._3SDvS') as $btn) {

            $style = pq($btn)->attr('style');
            if (preg_match('/\((.*?)\)/', $style, $matches)) {

                $bg = $matches[1];
                $bgx = explode(';', $bg);

//                $this->photos[] = trim($bgx[0]) . ";s=850x0";
                $this->photos[] = trim($bgx[0]);
            }
        }
    }

    protected function renderHtml() {

        $html = '';

        if ($this->price)
            $html .= '<div class="priceproduct">' . $this->price . '</div>';

        if ($this->specs) {

            $html .= '<div class="specsproduct"><ul class="list-group row">';

            foreach ($this->specs as $label => $value) {

                $html .= '<li class="list-group-item col-md-6">';
                $html .= '<span class="col-md-6 speclabel">' . $label . '</span>';
                $html .= '<span class="col-md-6 specvalue">' . $value . '</span>';
                $html .= '</li>';
            }

            $html .= '</ul></div>';
        }

        $html .= '<div class="row olxprofil">';
        $html .= '<div class="col-md-6">';
        $html .= '<ul class="galepro-core-socialicon-share">';
        $html .= '<li class="facebook"><a class="galepro-core-sharebtn galepro-core-facebook" href="' . $this->getUrl() . '" target="_blank">Hubungi iklan ini di OLX</a></li>';
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '<div class="col-md-6 berlaku">';
        $html .= '<ul><li>';
        $html .= 'Berlaku : ' . $this->start->format('d M Y') . ' s/d ' . $this->end->format('d M Y');
        $html .= '</li></li>';
        $html .= '</div>';
        $html .= '</div>';

        if ($this->desc) {

            $html .= '<div class="descproduct">';
            $html .= $this->desc;
            $html .= '</div>';
        }

        $this->content = $html;
    }

    protected function _getTags() {

        $this->tags = array_values($this->specs);
    }

    public function getPhotos() {

        return $this->photos;
    }

    protected function generateSeoMetaDescription() {

        $specs = implode(',', array_values($this->specs));
        $specs = rtrim(ucwords($specs), ',');
        $this->meta_description = "{$this->title} {$specs}.";
    }

    protected function generateSeoMetaTitle() {

        $this->meta_title = $this->title;
    }

    protected function tayang() {

        $mts = [
            'jan' => 'Jan',
            'feb' => 'Feb',
            'mar' => 'Mar',
            'apr' => 'Apr',
            'mei' => 'May',
            'jun' => 'Jun',
            'jul' => 'Jul',
            'aug' => 'Aug',
            'sep' => 'Sep',
            'okt' => 'Oct',
            'nov' => 'Nov',
            'des' => 'Dec'
        ];

        $dv = pq('div[data-aut-id="itemCreationDate"]')->text();
        $dv = strtolower(trim($dv));

        $tz = new \DateTimeZone(get_option('timezone_string'));
        $this->start = new \DateTime(current_time('mysql'), $tz);

        if ($dv == 'hari ini') {
            
        } elseif ($dv == 'kemarin') {

            $int = 1;
            $this->start->modify("-{$int} day");
        } elseif (preg_match('/(.*?)hari\syang\slalu/', $dv, $m)) {

            $int = trim($m[1]);
            $this->start->modify("-{$int} day");
        } elseif (preg_match('/(.*?)(jan|feb|mar|apr|mei|jun|jul|aug|sep|okt|nov|des)/', $dv, $mt)) {

            $dt = trim($mt[1]);
            $mo = trim($mt[2]);
            $yr = date('Y');

            $this->start = \DateTime::createFromFormat('d M Y', "{$dt} {$mts[$mo]} {$yr}", $tz);
        }

        $this->end = clone $this->start;

        if ($this->source_category >= 7 && $this->source_category <= 12)
            $expr = 90; //tayang properti 90 hari
        else
            $expr = 30; //tayang kategori lain 30 hari

        $this->end->add(new \DateInterval("P{$expr}D"));

        $this->tayang['start'] = $this->start->format('Y-m-d H:i:s');
        $this->tayang['end'] = $this->end->format('Y-m-d H:i:s');
    }

}
