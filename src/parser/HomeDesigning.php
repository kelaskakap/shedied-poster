<?php

namespace SheDied\parser;

use SheDied\parser\AbstractParser;

class HomeDesigning extends AbstractParser {

    protected $gallery = [];
    protected $prologue = '';

    protected function getPostDetail() {

        $doc = $this->curlGrabContent();

        if (function_exists('mb_convert_encoding')) {

            $doc = mb_convert_encoding($doc, "HTML-ENTITIES", "UTF-8");
        }

        $html = \phpQuery::newDocument($doc);

        $prologue = pq('div.top_blocks p.first')->text();
        if (!trim($prologue))
            $prologue = pq('div.entry p:first')->text();

        $this->prologue = trim($prologue);

        foreach (pq('div.entry > div.uniq-class > div.simplepost > p img') as $img) {

            $simg = pq($img)->parent('a')->attr('href');
            $alt = pq($img)->attr('alt');
            $alt = ucwords(trim($alt));

            $this->gallery[] = [
                'image' => trim($simg),
                'excerpt' => $alt,
                'caption' => $alt
            ];
        }

        if (!$this->gallery) {

            foreach (pq('div.gallery-item') as $div) {

                $image = pq($div)->find('img.attachment-thumbnail');
                $foto = $image->parent('a')->attr('href');
                $foto = trim($foto);

                if (!preg_match('/\.(jpg|jpeg|png|gif)(?:[\?\#].*)?$/i', $foto)) {

                    $height = trim($image->attr('height'));
                    $width = trim($image->attr('width'));
                    $pattern = "/\-{$width}x{$height}/";
                    $src = trim($image->attr('src'));

                    $foto = preg_replace($pattern, "", $src);
                }

                $caption = pq($div)->find('dd.gallery-caption')->text();
                $author = pq($div)->find('ul.postmeta > li:last-child')->text();

                $this->gallery[] = [
                    'image' => $foto,
                    'excerpt' => trim($caption),
                    'caption' => trim($author)
                ];
            }
        }

        $this->content = $this->prologue;
    }

    public function grab() {

        $this->getPostDetail();
        $this->_getFeaturedImage();
        $this->aggregateContent();
        $this->cleanUp();
        $this->_getHost();
        $this->generateSeoMetaDescription();
        $this->generateSeoMetaKeywords();
    }

    protected function _getFeaturedImage() {

        if ($this->gallery) {

            $this->featured_image = $this->gallery[0]['image'];
        }
    }

    protected function generateSeoMetaDescription() {

        $meta_description = pq('meta[name="description"]')->attr('content');
        $this->meta_description = trim($meta_description);
    }

    protected function generateSeoMetaKeywords() {

        $meta_keywords = pq('meta[name="keywords"]')->attr('content');
        $this->meta_keywords = trim($meta_keywords);
    }

    public function getGallery() {

        return $this->gallery;
    }

}
