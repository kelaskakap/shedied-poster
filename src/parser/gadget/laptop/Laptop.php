<?php

namespace SheDied\parser\gadget\laptop;

use SheDied\parser\gadget\Gadget;

abstract class Laptop extends Gadget implements ILaptop
{

    const LAPTOP_CATEGORY_ID = 154;

    public function __construct()
    {

        parent::__construct();
        $this->default_Scores();
        $this->addCategoryId(self::LAPTOP_CATEGORY_ID);
    }

    protected function default_Scores()
    {

        $this->scores = [
                [
                'review_criteria' => 'Design',
                'review_score' => parent::DEFAULT_RATE
            ],
                [
                'review_criteria' => 'Features',
                'review_score' => parent::DEFAULT_RATE
            ],
                [
                'review_criteria' => 'Performance',
                'review_score' => parent::DEFAULT_RATE
            ],
                [
                'review_criteria' => 'Battery',
                'review_score' => parent::DEFAULT_RATE
            ],
                [
                'review_criteria' => 'Price',
                'review_score' => parent::DEFAULT_RATE
            ]
        ];
    }

    protected function generateSeoMetaDescription()
    {

        $model = $this->model;
        $this->meta_description = "{$model} specifications, {$model} price, download driver {$model}, {$model} driver Windows 7, {$model} driver Windows 8.1, {$model} driver Windows 10, {$model} reviews, buy {$model}, where to buy {$model}";
    }

    protected function generateSeoMetaKeywords()
    {

        $this->meta_keywords = "{$this->brand},{$this->model},{$this->meta_description}";
    }

    protected function generateSeoMetaTitle()
    {

        $this->meta_title = $this->title;
    }

}
