<?php

namespace SheDied\parser\gadget;

use SheDied\parser\AbstractParser;
use SheDied\parser\gadget\IGadget;

abstract class Gadget extends AbstractParser implements IGadget
{

    const DEFAULT_RATE = 3;

    protected $brand;
    protected $model;
    protected $plink;
    protected $photos = [];
    protected $specs = [];
    protected $desc;
    protected $support;
    protected $author_rate = self::DEFAULT_RATE;
    protected $scores = [];

    abstract protected function dom_Content();

    abstract protected function dom_Model();

    abstract protected function dom_Specs();

    abstract protected function dom_Support();

    abstract protected function dom_Gallery();

    abstract protected function default_Scores();

    public function getBrand()
    {

        return $this->brand;
    }

    public function getModel()
    {

        return $this->model;
    }

    public function getProductLink()
    {

        return $this->plink;
    }

    public function getProductPhotos()
    {

        return $this->photos;
    }

    public function getProductSpecs()
    {

        return $this->specs;
    }

    public function setBrand($brand)
    {

        $this->brand = $brand;
        return $this;
    }

    public function setModel($model)
    {

        $this->model = $model;
        return $this;
    }

    public function setProductLink($plink)
    {

        $this->plink = $plink;
        return $this;
    }

    public function setProductPhotos($photos)
    {

        $this->photos = $photos;
        return $this;
    }

    public function setProductSpecs($specs)
    {

        $this->specs = $specs;
        return $this;
    }

    public function specsTable()
    {

        $html = "<table class='table gadget-specs'>";
        $html .= "<tbody>";

        foreach ($this->specs as $label => $value)
        {

            $html .= "<tr>";
            $html .= "<td>{$label}<td>";
            $html .= "<td>{$value}</td>";
            $html .= "</tr>";
        }

        $html .= "</tbody>";
        $html .= "</table>";

        return $html;
    }

    public function setProductDesc($desc)
    {

        $this->desc = $desc;
        return $this;
    }

    public function getProductDesc()
    {

        return $this->desc;
    }

    public function setProductSupport($param)
    {

        $this->support = $param;
        return $this;
    }

    public function getProductSupport()
    {

        return $this->support;
    }

    public function getAuthor_Rate()
    {

        return $this->author_rate;
    }

    public function getScores()
    {

        return $this->scores;
    }

    public function toWordpressPost()
    {

        //review-category is an array :)

        return array(
            'post_content' => $this->content,
            'post_status' => $this->status,
            'post_title' => ucwords($this->title),
            'post_author' => $this->author_id,
            'post_type' => $this->type,
            'post_date' => $this->time,
            'comment_status' => $this->comment_status,
            'tax_input' => [
                'review-category' => $this->category_id
            ]
        );
    }

}
