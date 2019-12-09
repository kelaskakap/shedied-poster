<?php

use SheDied\SheDieDConfig;
use SheDied\helpers\Lima;

function bot_olx_jogja_1() {

    $sources = SheDieDConfig::pick_Sources([1, 2, 3], [9, 10, 33]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_1', false);
}

add_action('bot_olx_jogja_1', 'bot_olx_jogja_1');

function bot_olx_jogja_2() {

    $sources = SheDieDConfig::pick_Sources([4, 5, 6], [34, 35, 36]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_2', false);
}

add_action('bot_olx_jogja_2', 'bot_olx_jogja_2');

function bot_olx_jogja_3() {

    $sources = SheDieDConfig::pick_Sources([7, 8, 9], [23, 37, 38]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_3', false);
}

add_action('bot_olx_jogja_3', 'bot_olx_jogja_3');

function bot_olx_jogja_4() {

    $sources = SheDieDConfig::pick_Sources([10, 11, 12], [39, 40, 41]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_4', false);
}

add_action('bot_olx_jogja_4', 'bot_olx_jogja_4');

function bot_olx_jogja_5() {

    $sources = SheDieDConfig::pick_Sources([10, 11, 12], [39, 40, 41]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_5', false);
}

add_action('bot_olx_jogja_5', 'bot_olx_jogja_5');

function bot_olx_jogja_6() {

    $sources = SheDieDConfig::pick_Sources([13, 14, 23], [57, 58, 71]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_6', false);
}

add_action('bot_olx_jogja_6', 'bot_olx_jogja_6');

function bot_olx_jogja_7() {

    $sources = SheDieDConfig::pick_Sources([15, 16, 17], [60, 61, 62]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_7', false);
}

add_action('bot_olx_jogja_7', 'bot_olx_jogja_7');

function bot_olx_jogja_8() {

    $sources = SheDieDConfig::pick_Sources([18, 19], [64, 65]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_8', false);
}

add_action('bot_olx_jogja_8', 'bot_olx_jogja_8');

function bot_olx_jogja_9() {

    $sources = SheDieDConfig::pick_Sources([20, 21, 22], [69, 67, 68]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_9', false);
}

add_action('bot_olx_jogja_9', 'bot_olx_jogja_9');

function bot_olx_jogja_sweeper() {

    $helper = new Lima();

    shedied_exec_bot($helper, [], 5, 'tsnt_olx_jogja_1', true);
    shedied_exec_bot($helper, [], 5, 'tsnt_olx_jogja_2', true);
    shedied_exec_bot($helper, [], 5, 'tsnt_olx_jogja_3', true);
    shedied_exec_bot($helper, [], 5, 'tsnt_olx_jogja_4', true);
    shedied_exec_bot($helper, [], 5, 'tsnt_olx_jogja_5', true);
    shedied_exec_bot($helper, [], 5, 'tsnt_olx_jogja_6', true);
    shedied_exec_bot($helper, [], 5, 'tsnt_olx_jogja_7', true);
    shedied_exec_bot($helper, [], 5, 'tsnt_olx_jogja_8', true);
    shedied_exec_bot($helper, [], 5, 'tsnt_olx_jogja_9', true);
}

add_action('bot_olx_jogja_sweeper', 'bot_olx_jogja_sweeper');
