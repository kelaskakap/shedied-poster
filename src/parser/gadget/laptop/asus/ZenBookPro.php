<?php

namespace SheDied\parser\gadget\laptop\asus;

use SheDied\parser\gadget\laptop\asus\Asus;

class ZenBookPro extends Asus {

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

}
