<?php

use SheDied\SheDieDConfig;
use SheDied\helpers\Dua;

function bot_awesomedecors_sweeper()
{
    $count = 0;
    $max = 1;
    $start = 2;
    $end = 71;
    $current = (int) get_transient('awesomedecors_next_sweep');
    $mapping = array_reverse(awesomedecors_mapping(), TRUE);

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
        $transient = "tsnt_awesomedecors_{$source}";

        shedied_exec_bot(new Dua(), [], 1, $transient, true);

        next($mapping);
        set_transient('awesomedecors_next_sweep', key($mapping));

        if ($count >= $max)
            break;
    }
}

add_action('bot_awesomedecors_sweeper', 'bot_awesomedecors_sweeper');

/**
 * [
 *    [$source => category]
 * ]
 * @return array
 */
function awesomedecors_mapping()
{
    return [
        2 => 2,
        3 => 3,
        6 => 6,
        7 => 7,
        9 => 9,
        10 => 10,
        11 => 11,
        12 => 12,
        13 => 13,
        14 => 14,
        16 => 16,
        17 => 17,
        18 => 18,
        19 => 19,
        20 => 20,
        21 => 21,
        22 => 6785,
        23 => 6786,
        24 => 6787,
        25 => 6788,
        26 => 6789,
        28 => 6790,
        29 => 4681,
        30 => 4681,
        31 => 8,
        32 => 6794,
        33 => 4681,
        34 => 4681,
        35 => 17,
        36 => 4681,
        37 => 6800,
        38 => 23,
        39 => 6,
        40 => 3,
        41 => 23,
        42 => 14,
        43 => 17,
        44 => 16,
        45 => 6819,
        46 => 6813,
        47 => 6814,
        48 => 13,
        49 => 13,
        50 => 13,
        51 => 10,
        52 => 6819,
        53 => 6825,
        54 => 17,
        55 => 17,
        56 => 6825,
        57 => 6825,
        61 => 13,
        62 => 8622,
        63 => 8622,
        64 => 13,
        65 => 13,
        66 => 12,
        67 => 13,
        70 => 7,
        71 => 13
    ];
}

function bot_awesomedecors_run()
{
    $count = 0;
    $max = 1;
    $start = 2;
    $end = 71;
    $current = (int) get_transient('awesomedecors_next_run');
    $mapping = array_reverse(awesomedecors_mapping(), TRUE);

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
        $transient = "tsnt_awesomedecors_{$source}";
        $frArrayName = "source_{$source}";
        $sources = SheDieDConfig::pick_Sources([$source], [$category]);

        $fr = first_Run($frArrayName);
        $helper = new Dua();
        $helper->yesFirstRun($fr);

        shedied_exec_bot($helper, $sources, 20, $transient, false);
        update_first_Run($frArrayName, $helper->arrFirstRun());

        next($mapping);
        set_transient('awesomedecors_next_run', key($mapping));

        if ($count >= $max)
            break;
    }
}

add_action('bot_awesomedecors_run', 'bot_awesomedecors_run');
