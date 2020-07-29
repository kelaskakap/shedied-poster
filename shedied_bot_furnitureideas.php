<?php

use SheDied\SheDieDConfig;
use SheDied\helpers\Tujuh;

function bot_furnitureideas_sweeper()
{
    $count = 0;
    $max = 1;
    $start = 2;
    $end = 36;
    $current = (int) get_transient('furnitureideas_next_sweep');
    $mapping = furnitureideas_mapping();

    if ($current < $start AND $current > $end)
        $current = $start;

    foreach ($mapping as $source => $category)
    {
        if ($current != $source)
        {
            next($mapping);
            continue;
        }

        $count++;
        $transient = "tsnt_furnitureideas_{$source}";

        shedied_exec_bot(new Tujuh(), [], 1, $transient, true);

        next($mapping);
        set_transient('furnitureideas_next_sweep', key($mapping));

        if ($count >= $max)
            break;
    }
}

add_action('bot_furnitureideas_sweeper', 'bot_furnitureideas_sweeper');

/**
 * [
 *    [$source => category]
 * ]
 * @return array
 */
function furnitureideas_mapping()
{
    return [
        2 => 14,
        3 => 3,
        6 => 13,
        7 => 15,
        9 => 15,
        10 => 17,
        11 => 11,
        12 => 14,
        13 => 1,
        14 => 12,
        15 => 12,
        16 => 12,
        17 => 12,
        18 => 15,
        19 => 17,
        20 => 15,
        21 => 1,
        22 => 17,
        23 => 17,
        24 => 17,
        25 => 17,
        26 => 11,
        27 => 17,
        28 => 11,
        29 => 15,
        30 => 17,
        31 => 17,
        32 => 14,
        33 => 13,
        34 => 15,
        35 => 15,
        36 => 17
    ];
}

function bot_furnitureideas_run()
{
    $count = 0;
    $max = 1;
    $start = 2;
    $end = 36;
    $current = (int) get_transient('furnitureideas_next_run');
    $mapping = furnitureideas_mapping();

    if ($current < $start AND $current > $end)
        $current = $start;

    foreach ($mapping as $source => $category)
    {
        if ($current != $source)
        {
            next($mapping);
            continue;
        }

        $count++;
        $transient = "tsnt_furnitureideas_{$source}";
        $frArrayName = "source_{$source}";
        $sources = SheDieDConfig::pick_Sources([$source], [$category]);

        $fr = first_Run($frArrayName);
        $helper = new Tujuh();
        $helper->yesFirstRun($fr);

        shedied_exec_bot($helper, $sources, 20, $transient, false);

        next($mapping);
        set_transient('furnitureideas_next_run', key($mapping));

        if ($count >= $max)
            break;
    }
}

add_action('bot_furnitureideas_run', 'bot_furnitureideas_run');
