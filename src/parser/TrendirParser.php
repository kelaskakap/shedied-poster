<?php

namespace SheDied\parser;

use SheDied\parser\AbstractParserWithGallery;

class TrendirParser extends AbstractParserWithGallery
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
        $node = pq('div.post-content');

        //remove ads
        $node->find('div.adthrive-ad')->remove();
        //remove noscript
        $node->find('noscript')->remove();
        $node->find('div.yarpp-related')->remove();
        $node->find('div.pub')->remove();

        $counter_p = 0;

        foreach (pq($node)->find('*') as $i => $el)
        {
            $index = self::idx . $i;

            if (pq($el)->is('p') OR pq($el)->is('h2') OR pq($el)->is('fgcaption'))
            {
                $p = trim(pq($el)->text());
                if ($p)
                {
                    $this->p[$index] = $p;
                    $counter_p++;
                }
            } elseif (pq($el)->is('figure'))
            {
                $s = pq($el)->find('source > source img');
                $img = trim(pq($s)->attr('data-lazy-srcset'));
                $excerpt = $this->title;
                $caption = $this->title;

                $image = $this->setPhotoSource($img, $excerpt, $caption);
                $this->gallery[$index] = $image;
                $this->p[$index] = $image;
            }
        }

        if (!$this->gallery)
        {
            ++$counter_p;
            foreach (pq($node)->find('img') as $i => $img)
            {
                $index = self::idx . $counter_p;
                $photo = trim(pq($img)->attr('data-lazy-src'));
                if (empty($photo))
                    $photo = trim(pq($img)->attr('src'));
                
                $alt = $this->title;
                $image = $this->setPhotoSource($photo, $alt, $alt);

                $this->gallery[$index] = $image;
                $counter_p++;
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
        $gallery = $this->gallery;

        foreach ($this->p as $idx => $p)
        {
            if (isset($gallery[$idx]))
            {

                $img = $gallery[$idx];
                $content .= '<p>';
                $content .= $img['html'];
                $content .= '</p>';

                // dihapus
                unset($gallery[$idx]);
            } else
            {
                $content .= '<p>';
                $content .= $p;
                $content .= '</p>';
            }
        }

        if ($gallery)
        {
            //turahane di-looping dab. eman le nyolong
            foreach ($gallery as $g)
            {

                $content .= '<p>';
                $content .= $g['html'];
                $content .= '</p>';
            }
        }

        return $content;
    }

}
