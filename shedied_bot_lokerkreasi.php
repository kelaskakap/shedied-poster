<?php

use SheDied\SheDieDConfig;
use SheDied\PojokJogjaController;
use SheDied\helpers\Satu;
use SheDied\helpers\Dua;
use SheDied\helpers\Tiga;

function shedied_exec_bot($sources = [], $count = 1, $transient_name = '', $sweeper = false) {

    try {

        $post_links = get_transient($transient_name);
        $controller = new PojokJogjaController();
        $helper = null;

        if (SheDieDConfig::SITE_DOMAIN == Satu::LOKERKREASI_COM)
            $helper = new Satu ();
        elseif (SheDieDConfig::SITE_DOMAIN == Dua::AWESOMEDECORS_US)
            $helper = new Dua ();
        elseif (SheDieDConfig::SITE_DOMAIN == Tiga::POJOKJOGJA_COM)
            $helper = new Tiga ();

        if (empty($post_links) && !$sweeper && !empty($sources) && $helper) {

            foreach ($sources as $sourceId => $source) {

                $controller->setUrl($source['url']);
                $controller->setNewsSrc($sourceId);
                $controller->setCategory($source['cat']);
                $helper->fetchPostLinks($controller);
            }

            $post_links = $controller->getPostLinks();
            set_transient($transient_name, $post_links, DAY_IN_SECONDS);
            syslog(LOG_DEBUG, '[shedied bot] - update transient ' . $transient_name . ' count(' . count($post_links) . ')');
        }

        if (!empty($post_links) && $sweeper) {

            $to_run = array_slice($post_links, 0, $count);
            $to_save = array_slice($post_links, $count, count($post_links));

            if (!empty($to_run) && $helper) {

                $controller->setBulkPostType('post')
                        ->setAuthor(SheDieDConfig::AUTHOR_ID) //bot
                        ->setBulkPostStatus('publish')
                        ->setInterval(['value' => SheDieDConfig::BOT_POST_INVTERVAL, 'type' => 'minutes'])
                        ->setCount($count)
                        ->hijack(false)
                        ->isAuto(true)
                        ->setPostLinks($to_run)
                        ->botPosts($helper);
            }

            set_transient($transient_name, $to_save, DAY_IN_SECONDS);
        }
    } catch (\Exception $e) {

        syslog(LOG_ERR, '[shedied bot] - ' . $e->getMessage());
    }
}

function bot_lokerkreasi_1() {

    $sources = array_slice(SheDieDConfig::getSourcesList(), 0, 10, true);
    $sources = array_map(function($i) {
        $i['cat'] = 5;
        return $i;
    }, $sources);

    shedied_exec_bot($sources, 20, 'tsnt_lokerkreasi_1', false);
}

add_action('bot_lokerkreasi_1', 'bot_lokerkreasi_1');

function bot_lokerkreasi_2() {

    $sources = array_slice(SheDieDConfig::getSourcesList(), 10, 10, true);
    $sources = array_map(function($i) {
        $i['cat'] = 5;
        return $i;
    }, $sources);

    shedied_exec_bot($sources, 20, 'tsnt_lokerkreasi_2', false);
}

add_action('bot_lokerkreasi_2', 'bot_lokerkreasi_2');

function bot_lokerkreasi_3() {

    $sources = array_slice(SheDieDConfig::getSourcesList(), 20, 10, true);
    $sources = array_map(function($i) {
        $i['cat'] = 5;
        return $i;
    }, $sources);

    shedied_exec_bot($sources, 20, 'tsnt_lokerkreasi_3', false);
}

add_action('bot_lokerkreasi_3', 'bot_lokerkreasi_3');

function bot_lokerkreasi_4() {

    $sources = array_slice(SheDieDConfig::getSourcesList(), 30, 10, true);
    $sources = array_map(function($i) {
        $i['cat'] = 5;
        return $i;
    }, $sources);

    shedied_exec_bot($sources, 20, 'tsnt_lokerkreasi_4', false);
}

add_action('bot_lokerkreasi_4', 'bot_lokerkreasi_4');

function bot_lokerkreasi_sweeper() {
    shedied_exec_bot([], 5, 'tsnt_lokerkreasi_1', true);
    shedied_exec_bot([], 5, 'tsnt_lokerkreasi_2', true);
    shedied_exec_bot([], 5, 'tsnt_lokerkreasi_3', true);
    shedied_exec_bot([], 5, 'tsnt_lokerkreasi_4', true);
}

add_action('bot_lokerkreasi_sweeper', 'bot_lokerkreasi_sweeper');
