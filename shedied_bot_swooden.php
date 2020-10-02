<?php

use SheDied\SheDieDConfig;
use SheDied\helpers\Wolu;

function bot_swooden_sweeper()
{
    $count = 0;
    $max = 1;
    $start = 2;
    $end = 25;
    $current = (int) get_transient('swooden_next_sweep');
    $mapping = array_reverse(swooden_mapping(), TRUE);

    if ($current < $start OR $current > $end)
    //$current = $start; // mapping gak di-reverse
        $current = $end; // mapping di-reverse

    foreach ($mapping as $source => $category)
    {
        if ($current != $source)
        {
            next($mapping);
            continue;
        }

        $count++;
        $transient = "tsnt_swooden_{$source}";

        shedied_exec_bot(new Wolu(), [], 1, $transient, true, 'draft');

        next($mapping);
        set_transient('swooden_next_sweep', key($mapping));

        if ($count >= $max)
            break;
    }
}

add_action('bot_swooden_sweeper', 'bot_swooden_sweeper');

/**
 * [
 *    [$source => category]
 * ]
 * @return array
 */
function swooden_mapping()
{
    return [
        2 => 2,
        3 => 8,
        6 => 12,
        7 => 2,
        9 => 15,
        10 => 13,
        11 => 14,
        12 => 17,
        13 => 12,
        14 => 13,
        15 => 18,
        16 => 19,
        17 => 14,
        18 => 2,
        19 => 2,
        20 => 19,
        21 => 19,
        22 => 17,
        23 => 13,
        24 => 2,
        25 => 18,
    ];
}

function bot_swooden_run()
{
    $count = 0;
    $max = 1;
    $start = 2;
    $end = 25;
    $current = (int) get_transient('swooden_next_run');
    $mapping = array_reverse(swooden_mapping(), TRUE);

    if ($current < $start OR $current > $end)
    //$current = $start; // mapping gak di-reverse
        $current = $end; // mapping di-reverse

    foreach ($mapping as $source => $category)
    {
        if ($current != $source)
        {
            next($mapping);
            continue;
        }

        $count++;
        $transient = "tsnt_swooden_{$source}";
        $frArrayName = "source_{$source}";
        $sources = SheDieDConfig::pick_Sources([$source], [$category]);

        $fr = first_Run($frArrayName);
        $helper = new Wolu();
        $helper->yesFirstRun($fr);

        shedied_exec_bot($helper, $sources, 20, $transient, false);
        update_first_Run($frArrayName, $helper->arrFirstRun());

        next($mapping);
        set_transient('swooden_next_run', key($mapping));

        if ($count >= $max)
            break;
    }
}

add_action('bot_swooden_run', 'bot_swooden_run');
