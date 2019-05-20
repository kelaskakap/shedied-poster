<?php

namespace SheDied\parser\gadget\laptop\asus;

use SheDied\parser\gadget\laptop\asus\Asus;

class ZenBook extends Asus {

    protected function getPostDetail() {

        $doc = $this->curlGrabContent();
        $html = $this->make_DOM($doc);

        $model = $this->dom_Model();
        $this->dom_Content();

        $this->_getFeaturedImage();

        $this->setModel(trim($model));
        $this->setProductLink($this->url);
        $this->setProductDesc($this->content);
        $this->dom_Links();

        $doc = $this->do_CURL($this->spec_link);
        $html = $this->make_DOM($doc);

        $this->dom_Specs();

        $doc = $this->do_CURL($this->gallery_link);
        $html = $this->make_DOM($doc);

        $this->dom_Gallery();

        $support = $this->dom_Support();

        $this->setProductSupport($support);
    }

    public function grab() {

        $this->getPostDetail();
        $this->generateSeoMetaDescription();
        $this->generateSeoMetaKeywords();
        $this->generateSeoMetaTitle();
    }

    protected function dom_Model() {

        return pq('#ctl00_ContentPlaceHolder1_ctl00_span_model_name')->text();
    }

    protected function dom_Content() {

        $this->try_Content_1();

        if (!$this->content)
            $this->try_Content_2();
    }

    protected function dom_Specs() {

        $this->try_Specs_1();

        if (!$this->specs)
            $this->try_Specs_2();

        if (!$this->specs)
            $this->try_Specs_3();
    }

    protected function try_Specs_1() {

        $node = pq('div.insoweTable');

        foreach ($node->find('section') as $section) {

            $label = trim(pq($section)->find('div.insoweCol1')->text());
            $value = trim(pq($section)->find('div.insoweCol2')->find('p')->removeAttr('*')->html());

            $this->specs[$label] = $value;
        }
    }

    protected function try_Specs_2() {

        $node = pq('div#spec-area');

        foreach ($node->find('li') as $li) {

            $label = trim(pq($li)->find('span.spec-item')->text());
            $temp = pq($li)->find('div.spec-data');
            pq($temp)->find('*')->removeAttr('*');
            $value = trim(pq($temp)->html());

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

            $this->specs[$label] = $value;
        }
    }

    protected function dom_Support() {

        $info = "<p>Get more detailed information such as Driver Utility, FAQ, Manual & Document, Warranty Information of {$this->model}.</p>";
        $info .= "<p>";
        $info .= "<a class='btn btn-primary' href='{$this->support_link}' target='_blank' rel='noopener nofollow'>Go To Page {$this->model} Support</a>";
        $info .= "</p>";

        return $info;
    }

    protected function _getFeaturedImage() {

        $img = pq('meta[property="og:image"]')->attr('content');
        $this->featured_image = trim($img);
    }

    protected function try_Content_1() {

        $node = pq('div#AWD');

        if (!$node->size()) {
            $node = pq('div#CMD');
        }

        $text = "";

        foreach ($node->find('section') as $section) {

            $sub_title = trim(pq($section)->find('div.content__title')->text());
            $sub_info = trim(pq($section)->find('div.content__info')->text());
            $text .= "<h2 class=\"sell-point\">{$sub_title}</h2><p>{$sub_info}</p>";
        }

        $this->content = $text;
    }

    protected function try_Content_2() {

        $node = pq('div#box-productOverview-containter');
        $text = "";

        foreach ($node->find('section') as $sec) {

            $st_s = 'div.slogan, div.subT-big, div.box-word-cent > div.subT';
            $si_s = 'div.box-intro, div.txt-cent-mid, div.txt-left-mid, div.txt-cent, div.txt-cent-big';

            $sub_title = trim(pq($sec)->find($st_s)->text());
            $sub_info = trim(pq($sec)->find($si_s)->text());
            $text .= "<h2 class=\"sell-point\">{$sub_title}</h2><p>{$sub_info}</p>";
        }

        $this->content = $text;
    }

    protected function generateSeoMetaDescription() {

        $model = $this->model;
        $this->meta_description = "{$model} specifications, {$model} price, download driver {$model}, {$model} driver Windows 7, {$model} driver Windows 8.1, {$model} driver Windows 10, {$model} reviews, buy {$model}, where to buy {$model}";
    }

    protected function generateSeoMetaKeywords() {

        $this->meta_keywords = "{$this->brand},{$this->model},{$this->meta_description}";
    }

    protected function generateSeoMetaTitle() {

        $this->meta_title = $this->title;
    }

}
