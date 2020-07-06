<?php

namespace SheDied\parser;

use SheDied\parser\InterfaceParser;

abstract class AbstractParser implements InterfaceParser
{

    protected $url;
    protected $category_id = [];
    protected $category_name;
    protected $featured_image;
    protected $no_image = false;
    protected $content;
    protected $title;
    protected $status;
    protected $author_id;
    protected $type;
    protected $tags = [];
    protected $time;
    protected $source_category;
    protected $host;
    protected $meta_title;
    protected $meta_description;
    protected $meta_keywords;
    protected $meta_description_length = 160;
    protected $node;
    protected $has_map = false;
    protected $latitude;
    protected $longitude;
    protected $comment_status = 'open';
    protected $link_content;

    const DEFAULT_ATTACH_ID = 186785; // jogja senja

    public function __construct()
    {
        
    }

    public function toArray()
    {
        return get_object_vars($this);
    }

    abstract protected function getPostDetail();

    protected function cleanContentObject(\phpQueryObject $node)
    {
        //remove iframe
        $node->find('iframe')->remove();

        //remove noscript
        $node->find('noscript')->remove();

        //clean p from attributs mostly style, class
        $node->find('p')->removeAttr('*');

        //<p><strong>content
        //to
        //<p>content
        $node->find('p > strong')->contentsUnwrap();

        //<li><p>content
        //to
        //<li>content
        $node->find('li > p')->contentsUnwrap();

        //<li style="">content
        //to
        //<li>content
        $node->find('li')->removeAttr('style');

        //remove br
        //$node->find('br')->remove();
    }

    protected function cleanUp()
    {
        $this->removeJSTags();
        $this->removeCSSTags();
        $this->removeInsTags();
        $this->removeEmptyHTMLTags();
    }

    protected function _getFeaturedImage()
    {
        
    }

    protected function _getTags()
    {
        
    }

    protected function generateSeoMetaTitle()
    {
        
    }

    protected function generateSeoMetaDescription()
    {
        
    }

    protected function generateSeoMetaKeywords()
    {
        $keywords = str_replace(' ', ',', $this->title);
        $this->meta_keywords = $keywords;
    }

    public function setUrl($url)
    {
        $this->url = strval($url);
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    /**
     * array of category Id
     * @param array $id
     * @return $this
     */
    public function setCategoryId($id)
    {
        $this->category_id = $id;
        return $this;
    }

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function setSourceCategory($cat)
    {
        $this->source_category = $cat;
        return $this;
    }

    public function getSourceCategory()
    {
        return $this->source_category;
    }

    public function setCategoryName($name)
    {
        $this->category_name = $name;
        return $this;
    }

    public function getCategoryName()
    {
        return $this->category_name;
    }

    public function setFeaturedImage($image)
    {
        $this->featured_image = $image;
        return $this;
    }

    public function getFeaturedImage()
    {
        return $this->featured_image;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setNoImage($bool)
    {
        $this->no_image = (bool) $bool;
        return $this;
    }

    public function getNoImage()
    {
        return $this->no_image;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setAuthorId($id)
    {
        $this->author_id = $id;
        return $this;
    }

    public function getAuthorId()
    {
        return $this->author_id;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTime($time)
    {
        $this->time = $time;
        return $this;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    public function getHost()
    {
        return $this->host;
    }

    protected function _getHost()
    {
        $url = parse_url($this->url);
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $url['host'], $regs))
        {
            $this->host = $regs['domain'];
        }
    }

    public function setMetaDescription($desc)
    {
        $this->meta_description = $desc;
        return $this;
    }

    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    public function setMetaKeywords($keys)
    {
        $this->meta_keywords = $keys;
        return $this;
    }

    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    public function setMetaTitle($title)
    {
        $this->meta_title = $title;
        return $this;
    }

    public function getMetaTitle()
    {
        return $this->title;
    }

    public function toWordpressPost()
    {

        //post_category is an array

        return array(
            'post_content' => $this->content,
            'post_status' => $this->status,
            'post_title' => ucwords($this->title),
            'post_category' => $this->category_id,
            'post_author' => $this->author_id,
            'post_type' => $this->type,
            'tags_input' => $this->tags,
            'post_date' => $this->time,
            'comment_status' => $this->comment_status
        );
    }

    public function grab()
    {
        
    }

    protected function curlGrabContent()
    {

        try
        {

            return $this->do_CURL($this->url);
        } catch (\Exception $ex)
        {

            syslog(LOG_DEBUG, '[shedied poster] - gagal grab konten - ' . $ex->getMessage());
        }
    }

    protected function do_CURL($url)
    {

        $ch = curl_init();
        $parse = parse_url($url);
        $host = $parse['host'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: $host"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36');
        curl_setopt($ch, CURLOPT_HEADER, false);
        $output = curl_exec($ch);

        $error = curl_error($ch);

        if ($error)
        {
            throw new \Exception($error);
        }

        curl_close($ch);

        return $output;
    }

    protected function setMetaDescriptionLength($description)
    {
        if (strlen($description) > $this->meta_description_length)
        {
            $offset = ($this->meta_description_length - 3) - strlen($description);
            $description = substr($description, 0, strrpos($description, ' ', $offset)) . '...';
        }
        return $description;
    }

    protected function aggregateContent()
    {
        $content = str_replace(array("\r\n", "\r"), "\n", $this->content);
        $lines = explode("\n", $content);
        $new_lines = [];
        foreach ($lines as $i => $line)
        {
            if (!empty($line))
            {
                $new_lines[] = trim($line);
            }
        }
        $this->content = implode($new_lines);
    }

    protected function removeEmptyHTMLTags()
    {
        $content = preg_replace('#<p[^>]*>(\xc2\xa0|\s|&nbsp;|</?\s?br\s?/?>)*</?p>#', '', $this->content);
        $content = preg_replace('#<div[^>]*>(\xc2\xa0|\s|&nbsp;|</?\s?br\s?/?>)*</?div>#', '', $content);
        $content = preg_replace('#<strong[^>]*>(\xc2\xa0|\s|&nbsp;|</?\s?br\s?/?>)*</?strong>#', '', $content);
        $content = preg_replace('#<p><strong[^>]*>(\xc2\xa0|\s|&nbsp;|</?\s?br\s?/?>)*</?strong></?p>#', '', $content);
        $this->content = $content;
    }

    protected function removeJSTags()
    {
        $content = preg_replace('/<script[^>]*>.*?<\/script>/', '', $this->content);
        $this->content = $content;
    }

    protected function removeInsTags()
    {
        $content = preg_replace('/<ins[^>]*>.*?<\/ins>/', '', $this->content);
        $this->content = $content;
    }

    protected function removeCSSTags()
    {
        $content = preg_replace('/<style[^>]*>.*?<\/style>/', '', $this->content);
        $this->content = $content;
    }

    protected function alterBlockQuote()
    {
        if (preg_match_all('/<blockquote[^>]*>(.*?)<\/blockquote>/s', $this->content, $matches, PREG_PATTERN_ORDER))
        {
            if (isset($matches[1]))
            {
                foreach ($matches[1] as $key => $blockquote)
                {
                    $new_texts = strip_tags(trim($blockquote));
                    $this->content = str_replace(trim($matches[0][$key]), '<blockquote>' . $new_texts . '</blockquote>', $this->content);
                }
            }
        }
    }

    public function hasMap()
    {
        return $this->has_map;
    }

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function setCommentStatus($status)
    {
        $status = strtolower(strval($status));
        if ($status == 'open' || $status == 'closed')
        {
            $this->comment_status = $status;
        }
        return $this;
    }

    public function getCommentStatus()
    {
        return $this->comment_status;
    }

    protected function cleanAllSpaces($string = '')
    {
        return preg_replace('/\s+/S', '', $string);
    }

    public function getDefaultAttachID()
    {
        return self::DEFAULT_ATTACH_ID;
    }

    /**
     * Create DOM \phpQuery
     * @param string $html
     * @return \phpQuery
     */
    protected function make_DOM($html)
    {

        if (function_exists('mb_convert_encoding'))
        {

            $html = mb_convert_encoding($html, "HTML-ENTITIES", "UTF-8");
        }

        return \phpQuery::newDocument($html);
    }

    /**
     * Logging Errors for Debugging
     * @param mixed $errors string or array
     */
    public function logError($errors)
    {

        $log = __DIR__ . "/../../shedied.log";

        if (is_array($errors))
        {

            $errors = json_encode($errors);
        }

        $file = fopen($log, "a");
        echo fwrite($file, "\n" . date('Y-m-d h:i:s') . " :: " . $errors);
        fclose($file);
    }

    /**
     * append category
     * @param int $id
     */
    public function addCategoryId($id)
    {

        array_push($this->category_id, $id);
        return $this;
    }

    public function setLinkContent($param)
    {

        $this->link_content = $param;
        return $this;
    }

}
