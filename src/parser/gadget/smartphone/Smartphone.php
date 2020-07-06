<?php

namespace SheDied\parser\gadget\smartphone;

use SheDied\parser\gadget\Gadget;

abstract class Smartphone extends Gadget implements ISmartphone
{

    const SMARTPHONE_CATEGORY_ID = 567;

    public function __construct()
    {

        parent::__construct();
        $this->default_Scores();
        $this->addCategoryId(self::SMARTPHONE_CATEGORY_ID);
    }

    protected function default_Scores()
    {

        $this->scores = [
                [
                'review_criteria' => 'Design and Materials',
                'review_score' => parent::DEFAULT_RATE
            ],
                [
                'review_criteria' => 'Display',
                'review_score' => parent::DEFAULT_RATE
            ],
                [
                'review_criteria' => 'User-friendliness',
                'review_score' => parent::DEFAULT_RATE
            ],
                [
                'review_criteria' => 'Camera & Video',
                'review_score' => parent::DEFAULT_RATE
            ],
                [
                'review_criteria' => 'Voice Quality',
                'review_score' => parent::DEFAULT_RATE
            ],
                [
                'review_criteria' => 'Data Speed',
                'review_score' => parent::DEFAULT_RATE
            ],
                [
                'review_criteria' => 'Battery Life',
                'review_score' => parent::DEFAULT_RATE
            ],
                [
                'review_criteria' => 'Gaming Experience',
                'review_score' => parent::DEFAULT_RATE
            ],
                [
                'review_criteria' => 'Antenna Quality',
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
        $this->meta_description = "{$model} specifications, {$model} price, {$model} features, {$model} reviews, buy {$model}, where to buy {$model}";
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
