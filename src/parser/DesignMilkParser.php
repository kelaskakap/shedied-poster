<?php

namespace SheDied\parser;

use SheDied\parser\AbstractParserWithGallery;

class DesignMilkParser extends AbstractParserWithGallery {

    const idx = "index-";

    public function __construct() {

        $this->attach = TRUE;
    }

    protected function getPostDetail() {

        $doc = $this->curlGrabContent();
        $html = $this->make_DOM($doc);
        $node = pq('div.content-column');

        foreach (pq($node)->find('p') as $i => $p) {

            $index = self::idx . $i;
            $img = pq($p)->find('img');

            if ($img->length) {

                $srcs = trim($img->attr('srcset'));
                $arr = explode(',', $srcs);
                $src = end($arr);                
                $photo = substr($src, 0, strrpos($src, ' '));
                $photo = trim($photo);
                                
                $alt = $this->title;
                $image = $this->setPhotoSource($photo, $alt, $alt);

                $this->gallery[$index] = $image;
                $this->p[$index] = $image;
            } else {

                $this->p[$index] = pq($p)->text();
            }
        }
        
        //temp content
        $this->content = "Lorem ipsum";
    }

    public function grab() {

        $this->getPostDetail();
        $this->_getFeaturedImage();
        $this->_getHost();
        $this->generateSeoMetaDescription();
    }

    protected function _getFeaturedImage() {

        if ($this->gallery) {

            $idx = self::idx . "0";
            $this->featured_image = $this->gallery[$idx]['image'];
        }
    }

    protected function generateSeoMetaDescription() {

        $meta_description = pq('meta[name="description"]')->attr('content');
        $this->meta_description = trim($meta_description);
    }

}
