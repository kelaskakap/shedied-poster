<?php

use SheDied\SheDieDConfig;
use SheDied\PojokJogjaController;
use SheDied\helpers\Numbers;
use SheDied\WPWrapper;

function shedied_exec_bot(Numbers $helper, $sources = [], $count = 1, $transient_name = '', $sweeper = false) {

    try {

        $post_links = get_transient($transient_name);
        $controller = new PojokJogjaController();
        $controller->isAuto(true);

        if (empty($post_links) && !$sweeper && !empty($sources)) {

            foreach ($sources as $sourceId => $source) {

                $controller->setNewsSrc($sourceId);
                $controller->setCategory($source['cat']);

                $Url = $helper->firstRunURL($source['url'], $sourceId, $controller);
                $controller->setUrl($Url);

                //gofood
                $params = WPWrapper::param_Query_for_Helper($helper);
                $helper->scanURL($controller, $params);               
                
                $helper->fetchPostLinks($controller);
            }

            $post_links = $controller->getPostLinks();
            set_transient($transient_name, $post_links, DAY_IN_SECONDS);

            syslog(LOG_DEBUG, '[shedied bot] - update transient ' . $transient_name . ' count(' . count($post_links) . ')');
        }

        if (!empty($post_links) && $sweeper) {

            $to_run = array_slice($post_links, 0, $count);
            $to_save = array_slice($post_links, $count, count($post_links));

            if (!empty($to_run)) {

                $controller->setAuthor(SheDieDConfig::AUTHOR_ID) //bot
                        ->setBulkPostStatus('publish')
                        ->setBulkPostType($helper->getPostType())
                        ->setInterval(['value' => SheDieDConfig::BOT_POST_INVTERVAL, 'type' => 'minutes'])
                        ->setCount($count)
                        ->hijack(false)
                        ->setPostLinks($to_run)
                        ->botPosts($helper);
            }

            set_transient($transient_name, $to_save, DAY_IN_SECONDS);
        }
    } catch (\Exception $e) {

        syslog(LOG_ERR, '[shedied bot] - ' . $e->getMessage());
    }
}

function first_Run($name) {

    $afr = get_transient('afr');

    if (!isset($afr[$name]))
        return [];
    else
        return $afr[$name];
}

function update_first_Run($name, $fr) {

    $afr = get_transient('afr');

    if (!$afr)
        $afr = [];

    $afr[$name] = $fr;

    set_transient('afr', $afr);
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
