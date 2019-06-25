<?php

use SheDied\SheDieDConfig;
use SheDied\helpers\Empat;

function bot_technoreview_1() {

    $sources = SheDieDConfig::pick_Sources([1, 2, 3], [155, 155, 155]);

    //$fr = first_Run('techno_1');
    
    $helper = new Empat();
    //$helper->yesFirstRun($fr);
    $helper->setPostType('review');

    shedied_exec_bot($helper, $sources, 20, 'tsnt_technoreview_1', false);

    //update_first_Run('techno_1', $helper->arrFirstRun());
}

add_action('bot_technoreview_1', 'bot_technoreview_1');

function bot_technoreview_2() {

    $sources = SheDieDConfig::pick_Sources([4, 5, 6], [155, 155, 155]);

    //$fr = first_Run('techno_2');
    
    $helper = new Empat();
    //$helper->yesFirstRun($fr);
    $helper->setPostType('review');

    shedied_exec_bot($helper, $sources, 20, 'tsnt_technoreview_2', false);

    //update_first_Run('techno_2', $helper->arrFirstRun());
}

add_action('bot_technoreview_2', 'bot_technoreview_2');

function bot_technoreview_3() {

    $sources = SheDieDConfig::pick_Sources([7, 8, 9, 10], [155, 155, 155, 155]);

    $fr = first_Run('techno_3');
    
    $helper = new Empat();
    $helper->yesFirstRun($fr);
    $helper->setPostType('review');

    shedied_exec_bot($helper, $sources, 20, 'tsnt_technoreview_3', false);

    update_first_Run('techno_3', $helper->arrFirstRun());
}

add_action('bot_technoreview_3', 'bot_technoreview_3');

function bot_technoreview_sweeper() {
    
    $helper = new Empat();
    $helper->setPostType('review');
    
    //shedied_exec_bot($helper, [], 5, 'tsnt_technoreview_1', true);
    //shedied_exec_bot($helper, [], 5, 'tsnt_technoreview_2', true);
    shedied_exec_bot($helper, [], 5, 'tsnt_awesomedecors_3', true);
    //shedied_exec_bot(new Dua(), [], 5, 'tsnt_awesomedecors_13', true);
}

add_action('bot_technoreview_sweeper', 'bot_technoreview_sweeper');
