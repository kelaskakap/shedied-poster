<?php

namespace SheDied\parser\gadget\laptop\asus;

use SheDied\parser\gadget\laptop\asus\Asus;

class ChromeBook extends Asus
{

    protected function getPostDetail()
    {

        $doc = $this->curlGrabContent();
        $html = $this->make_DOM($doc);

        $model = $this->dom_Model();
        $this->setModel(trim($model));
        $this->dom_Content();

        $this->_getFeaturedImage();

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

    public function grab()
    {

        $this->getPostDetail();
        $this->generateSeoMetaDescription();
        $this->generateSeoMetaKeywords();
        $this->generateSeoMetaTitle();
    }

    protected function try_Content_2()
    {

        $node = pq('div#box-productOverview-containter');
        $text = "";
        $st_s = 'div.slogan, div.subT-big, div.subT';
        $si_s = 'div.box-intro, div.txt-cent-mid, div.txt-left-mid, div.txt-cent, div.txt-cent-big, div.txt-left';
        $wr_s = "div.box-word-left, div.box-word-cent, div.wrap-wording-container, div.box-port-wording";

        foreach ($node->find($wr_s) as $wr)
        {

            $st = pq($wr)->find($st_s);
            $si = pq($wr)->find($si_s);

            if ($st->count() < 2)
            {

                $sub_title = trim($st->text());
                $sub_info = trim($si->text());
                $text .= "<h2 class=\"sell-point\">{$sub_title}</h2><p>{$sub_info}</p>";
            }
        }

        $this->content = $text;
    }

    protected function try_Content_1()
    {

        $node = pq('div#AWD');

        if (!$node->size())
        {

            $node = pq('div#CMD');
        }

        $text = "";
        $st_s = 'div.content__title';
        $si_s = 'div.content__info';
        $wr_s = "div.wd__content";

        foreach ($node->find($wr_s) as $wr)
        {

            $st = pq($wr)->find($st_s);
            $si = pq($wr)->find($si_s);

            $sub_title = trim($st->text());
            $sub_info = trim($si->text());

            $text .= "<h2 class=\"sell-point\">{$sub_title}</h2><p>{$sub_info}</p>";
        }

        $this->content = $text;
    }

}
