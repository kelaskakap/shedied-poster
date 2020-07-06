<?php

namespace SheDied\parser;

use SheDied\parser\AbstractParserWithGallery;

class DesignMilkParser extends AbstractParserWithGallery
{

    const idx = "index-";

    protected $bedotag = FALSE;

    public function __construct()
    {

        $this->attach = TRUE;
    }

    protected function getPostDetail()
    {

        $doc = $this->curlGrabContent();
        $html = $this->make_DOM($doc);
        $node = pq('div.content-column');

        foreach (pq($node)->find('p') as $i => $p)
        {

            $index = self::idx . $i;
            $img = pq($p)->find('img');

            if ($img->length)
            {

                $srcs = trim($img->attr('srcset'));
                $arr = explode(',', $srcs);
                $src = end($arr);
                $photo = substr($src, 0, strrpos($src, ' '));
                $photo = trim($photo);

                $alt = $this->title;
                $image = $this->setPhotoSource($photo, $alt, $alt);

                $this->gallery[$index] = $image;
                $this->p[$index] = $image;
            } else
            {

                $this->p[$index] = pq($p)->text();
            }
        }

        if (!$this->gallery)
        {

            //bedo tag. asem nganggo div
            $this->bedotag = TRUE;

            foreach (pq($node)->find('div[id^=attachment_]') as $i => $div)
            {

                $index = self::idx . $i;
                $img = pq($div)->find('img');

                if ($img->length)
                {

                    $srcs = trim($img->attr('srcset'));
                    $arr = explode(',', $srcs);
                    $src = end($arr);
                    $photo = substr($src, 0, strrpos($src, ' '));
                    $photo = trim($photo);

                    $alt = $this->title;
                    $image = $this->setPhotoSource($photo, $alt, $alt);

                    $this->gallery[$index] = $image;
                }
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

        if (!$this->bedotag)
        {

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
        } else
        {

            $gallery = $this->gallery;

            foreach ($this->p as $idx => $p)
            {

                $content .= '<p>';
                $content .= $p;
                $content .= '</p>';

                if (isset($gallery[$idx]))
                {

                    $img = $gallery[$idx];
                    $content .= '<p>';
                    $content .= $img['html'];
                    $content .= '</p>';

                    // dihapus
                    unset($gallery[$idx]);
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
        }

        return $content;
    }

}
