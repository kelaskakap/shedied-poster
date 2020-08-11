<?php

namespace SheDied\parser;

use SheDied\parser\AbstractParserWithGallery;

class MyBlessedLifeParser extends AbstractParserWithGallery
{

    const idx = "index-";

    public function __construct()
    {

        $this->attach = TRUE;
    }

    protected function getPostDetail()
    {

        $doc = $this->curlGrabContent();
        $html = $this->make_DOM($doc);
        $node = pq('div.entry-content');

        //remove ads
        $node->find('div.adthrive-ad')->remove();
        //remove noscript
        $node->find('noscript')->remove();

        foreach (pq($node)->find('*') as $i => $el)
        {
            $index = self::idx . $i;

            if (pq($el)->is('p'))
            {
                $p = trim(pq($el)->text());

                if ($p AND $p != '[pinit count=”horizontal”]')
                {
                    $this->p[$index] = $p;
                }
            } elseif (pq($el)->is('img'))
            {
                $img = trim(pq($el)->attr('data-lazy-src'));
                $excerpt = $this->title;
                $caption = $this->title;

                $image = $this->setPhotoSource($img, $excerpt, $caption);
                $this->gallery[$index] = $image;
                $this->p[$index] = $image;
            }
        }
        
        //temp content
        $this->content = "Lorem ipsum";
    }

    public function grab()
    {

        $this->getPostDetail();
        $this->_getFeaturedImage();
        $this->_getHost();
        $this->generateSeoMetaDescription();
    }

    protected function _getFeaturedImage()
    {

        if ($this->gallery)
        {

            //ternyata tidak selalu 0 ferguso
            //$idx = self::idx . "0";
            //pake reset
            $this->featured_image = reset($this->gallery)['image'];            
        }
    }

    protected function generateSeoMetaDescription()
    {

        $meta_description = pq('meta[name="description"]')->attr('content');
        $this->meta_description = trim($meta_description);
    }

    public function buildPostWithGallery()
    {
        $content = '';

        foreach ($this->p as $idx => $p)
        {

            if (isset($this->gallery[$idx]))
            {

                $img = $this->gallery[$idx];
                $content .= '<p>';
                $content .= $img['html'];
                $content .= '</p>';
            } else
            {

                $content .= '<p>';
                $content .= $p;
                $content .= '</p>';
            }
        }

        return $content;
    }

}
