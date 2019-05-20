<?php

namespace SheDied\parser\gadget\laptop;

use SheDied\parser\gadget\Gadget;

abstract class Laptop extends Gadget implements ILaptop {

    public function __construct() {

        parent::__construct();
        $this->default_Scores();
    }

    protected function default_Scores() {

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

}
