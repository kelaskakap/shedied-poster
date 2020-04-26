<?php

use SheDied\SheDieDConfig;
use SheDied\helpers\Enam;

function bot_gofood_1() {

    $sources = SheDieDConfig::pick_Sources([1, 2], [6511, 6512]);

    $helper = new Enam();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_gofood_1', false);
}

function bot_gofood_2() {

    $sources = SheDieDConfig::pick_Sources([3, 4], [6513, 6514]);

    $helper = new Enam();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_gofood_2', false);
}

function bot_gofood_3() {

    $sources = SheDieDConfig::pick_Sources([5, 6], [6515, 6516]);

    $helper = new Enam();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_gofood_3', false);
}

function bot_gofood_4() {

    $sources = SheDieDConfig::pick_Sources([7, 8], [6517, 6518]);

    $helper = new Enam();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_gofood_4', false);
}

function bot_gofood_5() {

    $sources = SheDieDConfig::pick_Sources([9, 10], [6519, 6520]);

    $helper = new Enam();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_gofood_5', false);
}

function bot_gofood_6() {

    $sources = SheDieDConfig::pick_Sources([11, 12], [6521, 6527]);

    $helper = new Enam();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_gofood_6', false);
}

function bot_ngemie_sweeper() {

    $helper = new Enam();

    shedied_exec_bot($helper, [], 1, 'tsnt_gofood_1', true);
    shedied_exec_bot($helper, [], 1, 'tsnt_gofood_2', true);
    shedied_exec_bot($helper, [], 1, 'tsnt_gofood_3', true);
    shedied_exec_bot($helper, [], 1, 'tsnt_gofood_4', true);
    shedied_exec_bot($helper, [], 1, 'tsnt_gofood_5', true);
    shedied_exec_bot($helper, [], 1, 'tsnt_gofood_6', true);
}

add_action('bot_ngemie_sweeper', 'bot_ngemie_sweeper');
