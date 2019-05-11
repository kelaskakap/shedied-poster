<?php

use SheDied\SheDieDConfig;

function bot_awesomedecors_1() {

    $sources = SheDieDConfig::getSourcesList();
    shedied_exec_bot($sources[2], 20, 'tsnt_awesomedecors_1', false, 2);
    shedied_exec_bot($sources[3], 20, 'tsnt_awesomedecors_2', false, 3);
    shedied_exec_bot($sources[6], 20, 'tsnt_awesomedecors_3', false, 6);
    shedied_exec_bot($sources[7], 20, 'tsnt_awesomedecors_4', false, 7);
}

add_action('bot_awesomedecors_1', 'bot_awesomedecors_1');

function bot_awesomedecors_2() {

    $sources = SheDieDConfig::getSourcesList();
    shedied_exec_bot($sources[9], 20, 'tsnt_awesomedecors_5', false, 9);
    shedied_exec_bot($sources[10], 20, 'tsnt_awesomedecors_6', false, 10);
    shedied_exec_bot($sources[11], 20, 'tsnt_awesomedecors_7', false, 11);
    shedied_exec_bot($sources[12], 20, 'tsnt_awesomedecors_8', false, 12);
}

add_action('bot_awesomedecors_2', 'bot_awesomedecors_2');

function bot_awesomedecors_3() {

    $sources = SheDieDConfig::getSourcesList();
    shedied_exec_bot($sources[13], 20, 'tsnt_awesomedecors_9', false, 13);
    shedied_exec_bot($sources[14], 20, 'tsnt_awesomedecors_10', false, 14);
    shedied_exec_bot($sources[16], 20, 'tsnt_awesomedecors_11', false, 16);
    shedied_exec_bot($sources[17], 20, 'tsnt_awesomedecors_12', false, 17);
}

add_action('bot_awesomedecors_3', 'bot_awesomedecors_3');

function bot_awesomedecors_4() {

    $sources = SheDieDConfig::getSourcesList();
    shedied_exec_bot($sources[18], 20, 'tsnt_awesomedecors_13', false, 18);
    shedied_exec_bot($sources[19], 20, 'tsnt_awesomedecors_14', false, 19);
    shedied_exec_bot($sources[20], 20, 'tsnt_awesomedecors_15', false, 20);
    shedied_exec_bot($sources[21], 20, 'tsnt_awesomedecors_16', false, 21);
}

add_action('bot_awesomedecors_4', 'bot_awesomedecors_4');

function bot_awesomedecors_sweeper() {

    shedied_exec_bot([], 5, 'tsnt_awesomedecors_1', true);
    shedied_exec_bot([], 5, 'tsnt_awesomedecors_2', true);
    shedied_exec_bot([], 5, 'tsnt_awesomedecors_3', true);
    shedied_exec_bot([], 5, 'tsnt_awesomedecors_4', true);
    shedied_exec_bot([], 5, 'tsnt_awesomedecors_5', true);
    shedied_exec_bot([], 5, 'tsnt_awesomedecors_6', true);
    shedied_exec_bot([], 5, 'tsnt_awesomedecors_7', true);
    shedied_exec_bot([], 5, 'tsnt_awesomedecors_8', true);
    shedied_exec_bot([], 5, 'tsnt_awesomedecors_9', true);
    shedied_exec_bot([], 5, 'tsnt_awesomedecors_10', true);
    shedied_exec_bot([], 5, 'tsnt_awesomedecors_11', true);
    shedied_exec_bot([], 5, 'tsnt_awesomedecors_12', true);
    shedied_exec_bot([], 5, 'tsnt_awesomedecors_13', true);
    shedied_exec_bot([], 5, 'tsnt_awesomedecors_14', true);
    shedied_exec_bot([], 5, 'tsnt_awesomedecors_15', true);
    shedied_exec_bot([], 5, 'tsnt_awesomedecors_16', true);
}

add_action('bot_awesomedecors_sweeper', 'bot_awesomedecors_sweeper');
