<?php

namespace SheDied;

use SheDied\parser\Controller;
use SheDied\parser\CWriter;
use SheDied\parser\InterfaceParser;
use SheDied\WPWrapper;
use SheDied\SheDieDConfig;
use SheDied\helpers\Numbers;
use SheDied\helpers\Satu;
use SheDied\helpers\Dua;
use SheDied\helpers\Tiga;

class PojokJogjaController extends Controller {

    protected $news_src;
    protected $bulk_post_type;
    protected $category;
    protected $author;
    protected $bulk_post_status;
    protected $date;
    protected $interval;
    protected $action;
    protected $url;
    protected $post_links = [];
    protected $count = 20;
    protected $auto = false;
    protected $is_rewrite;
    protected $is_remove_links;
    protected $is_full_source;
    protected $additional = [];
    protected $hijack = false;
    protected $map_pois = [];
    protected $map_pois_collect = false;

    public function __construct() {

        parent::__construct();
        $this->getOptions();
    }

    protected function getOptions() {
        $this->is_rewrite = WPWrapper::get_option('shedied_isRewrite');
        $this->is_remove_links = WPWrapper::get_option('shedied_isRemoveLink');
        $this->is_full_source = WPWrapper::get_option('shedied_isFullSource');
        $rewriter['prefix'] = WPWrapper::get_option('shedied_firstpara');
        $rewriter['suffix'] = WPWrapper::get_option('shedied_lastpara');
        $this->additional = $rewriter;
    }

    public function setNewsSrc($news_src) {
        $this->news_src = $news_src;
        return $this;
    }

    public function getNewsSrc() {
        return $this->news_src;
    }

    public function setBulkPostType($type) {
        $this->bulk_post_type = $type;
        return $this;
    }

    public function setCategory($cat) {
        $this->category = $cat;
        return $this;
    }

    public function getCategory() {
        return $this->category;
    }

    public function setAuthor($author) {
        $this->author = $author;
        return $this;
    }

    public function setBulkPostStatus($status) {
        $this->bulk_post_status = $status;
        return $this;
    }

    public function setDate(\DateTime $date) {
        $this->date = $date;
        return $this;
    }

    public function setInterval($interval) {
        $this->interval = $interval;
        return $this;
    }

    public function setAction($action) {
        $this->action = $action;
        return $this;
    }

    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setCount($count) {
        $this->count = (int) $count;
        return $this;
    }

    public function getCount() {

        return $this->count;
    }

    public function isAuto($bool) {
        $this->auto = (bool) $bool;
        return $this;
    }

    public function setPostLinks($links) {
        $this->post_links = $links;
        return $this;
    }

    public function buildPosts() {

        $helper = null;

        if (SheDieDConfig::SITE_DOMAIN == Satu::LOKERKREASI_COM)
            $helper = new Satu();
        elseif (SheDieDConfig::SITE_DOMAIN == Dua::AWESOMEDECORS_US)
            $helper = new Dua();

        if ($helper && !$this->hijack && !$this->auto) {

            $helper->fetchPostLinks($this);
        }

        if ($helper) {

            $helper->switchParsers($this);
            $this->loopPostLinks($helper);
        }
    }

    protected function loopPostLinks(Numbers $helper) {

        $post_links = array_reverse($this->post_links);
        $key = 0;

        foreach ($post_links as $post_link) {

            if ($this->auto) {

                $this->news_src = $post_link['src'];
                $this->category = $post_link['cat'];
                $helper->switchParsers($this);
            }

            $title = CWriter::generatePostTitle($post_link['title']);
            $link = $post_link['link'];

            if (!WPWrapper::get_page_by_title($title) && $helper->getParser()) {

                $new = $helper->getParser();
                $parser = new $new;
                $parser->setTitle($title)
                        ->setSourceCategory($this->news_src)
                        ->setCategoryId($this->category)
                        ->setUrl($link)
                        ->grab();

                $gallery = true;
                if ($helper->need_Gallery())
                    if (!$parser->getGallery())
                        $gallery = false;


                if (strlen($parser->getContent()) > 0 && $gallery) {

                    CWriter::removeHtmlComments($parser);

                    if ($this->is_rewrite == "true") {
                        CWriter::rewrite($parser);
                    }

                    if ($this->is_remove_links == "true") {
                        CWriter::removeLinks($parser);
                    }

                    if ($this->is_full_source == "true") {
                        CWriter::rewriteURL($parser);
                    }

                    if (strlen($this->additional['prefix']) > 0 && strlen($this->additional['suffix']) > 0) {
                        CWriter::addPrefixSuffix($parser, $this->additional);
                    }

                    $new_draft_id = $this->createPost($parser, $key);

                    if ($new_draft_id > 0) {

                        WPWrapper::generate_featured_image($parser, $new_draft_id);

                        if (SheDieDConfig::SITE_DOMAIN == Satu::LOKERKREASI_COM) {

                            if ($parser->getHost() == 'jobstreet.co.id') {

                                WPWrapper::pojokjogja_set_source_for_jobstreet($new_draft_id, $parser->getHost(), $parser->getUrl(), $parser->getNamaPerusahaan());
                                WPWrapper::pojokjogja_set_expdate_jobstreet($new_draft_id, $parser);
                                WPWrapper::pojokjogja_set_tags_for_jobstreet($new_draft_id, $parser->getNamaPerusahaan(), $parser->getTags());
                            }
                        } elseif (SheDieDConfig::SITE_DOMAIN == Tiga::POJOKJOGJA_COM) {

                            if ($parser->getHost() == 'jobstreet.co.id') {

                                WPWrapper::pojokjogja_set_source_for_jobstreet($new_draft_id, $parser->getHost(), $parser->getUrl(), $parser->getNamaPerusahaan());
                                WPWrapper::pojokjogja_set_expdate_jobstreet($new_draft_id, $parser);
                                WPWrapper::pojokjogja_set_tags_for_jobstreet($new_draft_id, $parser->getNamaPerusahaan(), $parser->getTags());
                            } else {

                                WPWrapper::pojokjogja_set_source($new_draft_id, $parser->getHost(), $parser->getUrl());
                            }
                        } elseif (SheDieDConfig::SITE_DOMAIN == Dua::AWESOMEDECORS_US) {

                            WPWrapper::homedesigning_upload_gallery($parser, $new_draft_id);
                            WPWrapper::homedesigning_meta($new_draft_id, true, $parser->getHost(), $parser->getUrl());
                        }

                        WPWrapper::add_to_yoast_seo($new_draft_id, $parser->getMetaTitle(), $parser->getMetaDescription(), $parser->getMetaKeywords());

                        $this->echoSuccess($title, $new_draft_id);
                    }

                    syslog(LOG_DEBUG, 'key = ' . $key);
                    $key++;
                } else {

                    $this->echoFailed($title);
                }
            } else {
                $this->echoSkip($title);
            }
        }

        if ($key == 0) {
            $this->echoNoEntry();
        }
    }

    protected function echoSuccess($title, $new_draft_id) {

        if ($this->auto) {

            syslog(LOG_DEBUG, '[shedied bot] created - ' . $new_draft_id . ' - ' . $title);
        } else {

            echo $title . " created. <a href='post.php?action=edit&post=" . $new_draft_id . "' target='_blank'>Edit</a> |"
            . "<a href='" . WPWrapper::get_option('siteurl') . "?p=" . $new_draft_id . "' target='_blank'> View</a><br>" . PHP_EOL;
        }
    }

    protected function echoFailed($title) {

        if ($this->auto) {

            syslog(LOG_DEBUG, '[shedied bot] failed - ' . $title);
        } else {

            echo $title . " failed<br />";
        }
    }

    protected function echoSkip($title) {

        if ($this->auto) {

            syslog(LOG_DEBUG, '[shedied bot] skipped - ' . $title);
        } else {

            echo $title . " already posted. Skipped<br />";
        }
    }

    protected function echoNoEntry() {

        if ($this->auto) {

            syslog(LOG_DEBUG, '[shedied bot] no entry - belum ada berita baru');
        } else {

            echo "New posts are not found, please try again later";
        }
    }

    protected function createPost(InterfaceParser $parser, $key) {

        try {

            $base_date = new \DateTime(WPWrapper::current_time(), new \DateTimeZone(WPWrapper::get_option('timezone_string')));
            $post_interval = '+' . ((int) $this->interval['value'] * (int) $key) . ' ' . $this->interval['type'];
            $post_time = strtotime($post_interval, $base_date->getTimestamp());
            $base_date->setTimestamp($post_time);
            $parser->setAuthorId($this->author)
                    ->setType($this->bulk_post_type)
                    ->setTime($base_date->format('Y-m-d H:i:s'))
                    ->setStatus($this->bulk_post_status);

            if (!empty($parser->getTags())) {
                //$post_tag = WPWrapper::get_cat_name($parser->getTags());
                //$parser->setTags($post_tag);
            }

            $post_id = WPWrapper::wp_insert_post($parser);

            if ($post_id == 0) {
                syslog(LOG_ERR, '[shedied poster] - gagal simpan berita ' . $parser->getTitle());
            }

            return $post_id;
        } catch (\Exception $ex) {

            syslog(LOG_ERR, '[shedied poster] - gagal simpan berita ' . $parser->getTitle() . ' - ' . $ex->getMessage());
            return 0;
        }
    }

    public function hijack($bool) {

        $this->hijack = (bool) $bool;

        if ($this->hijack) {
            $this->count = 1;
            $this->post_links[] = [
                'title' => 'ko9iiik',
                'link' => 'http://www.home-designing.com/an-eclectic-minimalist-apartment'
            ];
        }

        return $this;
    }

    public function getPostLinks() {

        return $this->post_links;
    }

    public function botPosts(Numbers $helper) {

        $this->loopPostLinks($helper);
    }

}
