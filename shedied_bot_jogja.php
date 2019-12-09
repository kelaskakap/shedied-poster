<?php

use SheDied\SheDieDConfig;
use SheDied\helpers\Lima;

function bot_olx_jogja_1() {
    //3 jam sekali
    //1 mobil bekas
    //10 indekos
    //13 lowongan
    $sources = SheDieDConfig::pick_Sources([1, 10, 13], [9, 39, 57]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_1', false);
}

function bot_olx_jogja_2() {
    //3 jam sekali
    //9 tanah
    //8 disewakan rumah
    //12 disewakan banguna komersil
    $sources = SheDieDConfig::pick_Sources([9, 8, 12], [38, 37, 41]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_1', false);
}

add_action('bot_olx_jogja_2', 'bot_olx_jogja_2');

function bot_olx_jogja_3() {

    //6 jam sekali
    //18 handphone
    //19 laptop
    //23 hewan peliharaan
    $sources = SheDieDConfig::pick_Sources([18, 19, 23], [64, 65, 71]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_3', false);
}

add_action('bot_olx_jogja_3', 'bot_olx_jogja_3');

function bot_olx_jogja_4() {

    //6 jam sekali
    //14 jasa
    //16 perlengkapan usaha
    //21 mebel
    $sources = SheDieDConfig::pick_Sources([14, 16, 21], [58, 61, 67]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_4', false);
}

add_action('bot_olx_jogja_4', 'bot_olx_jogja_4');

function bot_olx_jogja_5() {

    //12 jam sekali
    //2 aksesori mobil
    //3 audio mobil
    //4 sparepart mobil
    $sources = SheDieDConfig::pick_Sources([2, 3, 4], [10, 33, 34]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_5', false);
}

add_action('bot_olx_jogja_5', 'bot_olx_jogja_5');

function bot_olx_jogja_6() {

    //12 jam sekali
    //6 truk kendaraan komersil
    //7 dijual rumah apartemen
    //11 dijual bangunan komersil
    $sources = SheDieDConfig::pick_Sources([6, 7, 11], [36, 23, 40]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_6', false);
}

add_action('bot_olx_jogja_6', 'bot_olx_jogja_6');

function bot_olx_jogja_7() {

    //24 jam sekali
    //15 peralatan kantor
    //17 keperluan industri
    //20 perlengkapan rumah
    $sources = SheDieDConfig::pick_Sources([15, 17, 20], [60, 62, 69]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_7', false);
}

add_action('bot_olx_jogja_7', 'bot_olx_jogja_7');

function bot_olx_jogja_8() {

    //24 jam sekali
    //21 mebel
    //22 dekorasi rumah
    //5 velg ban
    $sources = SheDieDConfig::pick_Sources([21, 22, 5], [67, 68, 35]);

    $helper = new Lima();

    shedied_exec_bot($helper, $sources, 20, 'tsnt_olx_jogja_8', false);
}

add_action('bot_olx_jogja_8', 'bot_olx_jogja_8');

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
}

add_action('bot_olx_jogja_sweeper', 'bot_olx_jogja_sweeper');
