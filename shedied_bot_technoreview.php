<?php

use SheDied\SheDieDConfig;
use SheDied\helpers\Empat;

/**
 * Bot yang sudah tidak "first run" dijalankan seminggu sekali
 * Bot sweeper tetap sesuai kebutuhan
 */

/**
 * laptop asus
 */
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

/**
 * laptop asus
 */
function bot_technoreview_2() {

    $sources = SheDieDConfig::pick_Sources([4, 5, 6], [155, 155, 155]);

    $helper = new Empat();
    $helper->setPostType('review');

    shedied_exec_bot($helper, $sources, 20, 'tsnt_technoreview_2', false);
}

add_action('bot_technoreview_2', 'bot_technoreview_2');

/**
 * laptop asus
 */
function bot_technoreview_3() {

    $sources = SheDieDConfig::pick_Sources([7, 8, 9, 10], [155, 155, 155, 155]);

    $helper = new Empat();
    $helper->setPostType('review');

    shedied_exec_bot($helper, $sources, 20, 'tsnt_technoreview_3', false);
}

add_action('bot_technoreview_3', 'bot_technoreview_3');

/**
 * laptop asus
 */
function bot_technoreview_4() {

    $sources = SheDieDConfig::pick_Sources([11, 12, 13, 14, 15], [155, 155, 155, 155, 155]);

    $helper = new Empat();
    $helper->setPostType('review');

    shedied_exec_bot($helper, $sources, 20, 'tsnt_technoreview_4', false);
}

add_action('bot_technoreview_4', 'bot_technoreview_4');

/**
 * gsm arena
 */
function bot_technoreview_5() {

    $sources = SheDieDConfig::pick_Sources([16, 17, 18, 19, 20], [568, 569, 570, 571, 572]);

    $fr = first_Run('techno_5');

    $helper = new Empat();    
    $helper->yesFirstRun($fr);
    $helper->setPostType('review');

    shedied_exec_bot($helper, $sources, 40, 'tsnt_technoreview_5', false);
    
    update_first_Run('techno_5', $helper->arrFirstRun());
}

add_action('bot_technoreview_5', 'bot_technoreview_5');

function bot_technoreview_sweeper() {

    $helper = new Empat();
    $helper->setPostType('review');

    shedied_exec_bot($helper, [], 5, 'tsnt_technoreview_1', true);
    shedied_exec_bot($helper, [], 5, 'tsnt_technoreview_2', true);
    shedied_exec_bot($helper, [], 5, 'tsnt_technoreview_3', true);
    shedied_exec_bot($helper, [], 5, 'tsnt_technoreview_4', true);
    shedied_exec_bot($helper, [], 5, 'tsnt_technoreview_5', true);
}

add_action('bot_technoreview_sweeper', 'bot_technoreview_sweeper');
