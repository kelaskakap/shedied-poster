<?php

use SheDied\SheDieDConfig;

function bot_awesomedecors_1() {

    $sources = SheDieDConfig::pick_Sources([2, 3, 6, 7], [2, 3, 6, 7]);
    shedied_exec_bot($sources, 20, 'tsnt_awesomedecors_1', false);
}

add_action('bot_awesomedecors_1', 'bot_awesomedecors_1');

function bot_awesomedecors_2() {

    $sources = SheDieDConfig::pick_Sources([9, 10, 11, 12], [9, 10, 11, 12]);
    shedied_exec_bot($sources, 20, 'tsnt_awesomedecors_5', false);
}

add_action('bot_awesomedecors_2', 'bot_awesomedecors_2');

function bot_awesomedecors_3() {

    $sources = SheDieDConfig::pick_Sources([13, 14, 16, 17], [13, 14, 16, 17]);
    shedied_exec_bot($sources, 20, 'tsnt_awesomedecors_9', false);
}

add_action('bot_awesomedecors_3', 'bot_awesomedecors_3');

function bot_awesomedecors_4() {

    $sources = SheDieDConfig::pick_Sources([18, 19, 20, 21], [18, 19, 20, 21]);
    shedied_exec_bot($sources, 20, 'tsnt_awesomedecors_13', false);
}

add_action('bot_awesomedecors_4', 'bot_awesomedecors_4');

function bot_awesomedecors_sweeper() {

    shedied_exec_bot([], 5, 'tsnt_awesomedecors_1', true);
    shedied_exec_bot([], 5, 'tsnt_awesomedecors_5', true);
    shedied_exec_bot([], 5, 'tsnt_awesomedecors_9', true);
    shedied_exec_bot([], 5, 'tsnt_awesomedecors_13', true);
}

add_action('bot_awesomedecors_sweeper', 'bot_awesomedecors_sweeper');
