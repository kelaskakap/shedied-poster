<?php

use SheDied\SheDieDConfig;
use SheDied\helpers\Tujuh;

function bot_furnitureideas_1() {

    //Architecture, Home Furnishing
    $sources = SheDieDConfig::pick_Sources([2, 3], [2106, 2]);

    $fr = first_Run('milkdesign_1');
    $helper = new Tujuh();
    $helper->yesFirstRun($fr);

    shedied_exec_bot($helper, $sources, 20, 'tsnt_furnitureideas_1', false);

    update_first_Run('milkdesign_1', $helper->arrFirstRun());
}

add_action('bot_furnitureideas_1', 'bot_furnitureideas_1');

function bot_furnitureideas_2() {

    //Interior Designs, Art
    $sources = SheDieDConfig::pick_Sources([6, 7], [2, 2107]);

    $fr = first_Run('milkdesign_5');
    $helper = new Tujuh();
    $helper->yesFirstRun($fr);

    shedied_exec_bot($helper, $sources, 20, 'tsnt_furnitureideas_5', false);

    update_first_Run('milkdesign_5', $helper->arrFirstRun());
}

add_action('bot_furnitureideas_2', 'bot_furnitureideas_2');

function bot_furnitureideas_3() {

    //Lifestyle, Pets
    $sources = SheDieDConfig::pick_Sources([9, 10], [1, 1]);

    $fr = first_Run('milkdesign_9');
    $helper = new Tujuh();
    $helper->yesFirstRun($fr);

    shedied_exec_bot($helper, $sources, 20, 'tsnt_furnitureideas_9', false);

    update_first_Run('milkdesign_9', $helper->arrFirstRun());
}

add_action('bot_furnitureideas_3', 'bot_furnitureideas_3');

function bot_furnitureideas_4() {

    //At the Office, Destination Design
    $sources = SheDieDConfig::pick_Sources([11, 12], [2108, 1]);

    $fr = first_Run('milkdesign_13');
    $helper = new Tujuh();
    $helper->yesFirstRun($fr);

    shedied_exec_bot($helper, $sources, 20, 'tsnt_furnitureideas_13', false);

    update_first_Run('milkdesign_13', $helper->arrFirstRun());
}

function bot_furnitureideas_sweeper() {

    //milkdesign
    $now = (int) date('H');

    if ($now < 13) {

        //siang
        shedied_exec_bot(new Tujuh(), [], 1, 'tsnt_furnitureideas_1', true);
        shedied_exec_bot(new Tujuh(), [], 1, 'tsnt_furnitureideas_5', true);
    } else {
        //malam
        shedied_exec_bot(new Tujuh(), [], 1, 'tsnt_furnitureideas_9', true);
        shedied_exec_bot(new Tujuh(), [], 1, 'tsnt_furnitureideas_13', true);
    }
}

add_action('bot_furnitureideas_sweeper', 'bot_furnitureideas_sweeper');

