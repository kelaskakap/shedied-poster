<?php

namespace SheDied\helpers;

use SheDied\helpers\Numbers;
use SheDied\PojokJogjaController;

/**
 * Lokerkreasi.com
 */
class Satu extends Numbers {

    const LOKERKREASI_COM = 'lokerkreasi.com';

    public function fetchPostLinks(PojokJogjaController $controller) {

        $doc = @file_get_contents($controller->getUrl());
        if (function_exists('mb_convert_encoding')) {
            $doc = mb_convert_encoding($doc, "HTML-ENTITIES", "UTF-8");
        }

        \phpQuery::newDocument($doc);

        $postlinks = [];

        if ($controller->getNewsSrc() > 1 && $controller->getNewsSrc() < 100) {
            #Jobstreet
            foreach (pq('div.position-title.header-text a') as $a) {
                $link = pq($a)->attr('href');
                $title = pq($a)->elements[0]->nodeValue;
                $postlinks[] = array("title" => trim($title), "link" => trim($link), 'src' => $controller->getNewsSrc(), 'cat' => $controller->getCategory());
            }
        }

        $controller->setPostLinks($postlinks);
    }

    public function switchParsers(PojokJogjaController $controller) {

        switch ($controller->getNewsSrc()) {
            case $controller->getNewsSrc() > 1 && $controller->getNewsSrc() < 100:
                $this->parser = 'SheDied\parser\JobstreetParser';
            default :
                break;
        }
    }

    static public function sources() {

        $sources[11] = ['name' => 'Jobstreet: Aceh', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30100&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[12] = ['name' => 'Jobstreet: Bali', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30200&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[13] = ['name' => 'Jobstreet: Bangka Belitung', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32800&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[14] = ['name' => 'Jobstreet: Banten', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32900&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[16] = ['name' => 'Jobstreet: Bengkulu', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30300&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[17] = ['name' => 'Jobstreet: Gorontalo', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=33000&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[6] = ['name' => 'Jobstreet: Jakarta Raya', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30500&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[18] = ['name' => 'Jobstreet: Jambi', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30600&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[9] = ['name' => 'Jobstreet: Jawa Barat', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30700&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[8] = ['name' => 'Jobstreet: Jawa Tengah', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30800&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[7] = ['name' => 'Jobstreet: Jawa Timur', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30900&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[19] = ['name' => 'Jobstreet: Kalimantan Barat', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31000&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[20] = ['name' => 'Jobstreet: Kalimantan Selatan', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31100&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[21] = ['name' => 'Jobstreet: Kalimantan Tengah', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31200&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[22] = ['name' => 'Jobstreet: Kalimantan Timur', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31300&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[23] = ['name' => 'Jobstreet: Kalimantan Utara', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=33500&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[24] = ['name' => 'Jobstreet: Kepulauan Riau', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=33200&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[15] = ['name' => 'Jobstreet: Lampung', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31400&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[25] = ['name' => 'Jobstreet: Maluku', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31500&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[26] = ['name' => 'Jobstreet: Maluku Utara', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=33100&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[27] = ['name' => 'Jobstreet: Nusa Tenggara Barat', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31600&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[28] = ['name' => 'Jobstreet: Nusa Tenggara Timur', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31700&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[29] = ['name' => 'Jobstreet: Papua', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=30400&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[30] = ['name' => 'Jobstreet: Papua Barat', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=33300&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[31] = ['name' => 'Jobstreet: Riau', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31800&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[32] = ['name' => 'Jobstreet: Sulawesi Barat', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=33400&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[33] = ['name' => 'Jobstreet: Sulawesi Selatan', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=31900&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[34] = ['name' => 'Jobstreet: Sulawesi Tengah', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32000&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[36] = ['name' => 'Jobstreet: Sulawesi Tenggara', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32100&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[35] = ['name' => 'Jobstreet: Sulawesi Utara', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32200&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[37] = ['name' => 'Jobstreet: Sumatera Barat', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32300&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[38] = ['name' => 'Jobstreet: Sumatera Selatan', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32400&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[39] = ['name' => 'Jobstreet: Sumatera Utara', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32500&specialization=&area=&salary=&ojs=3&src=12'];
        $sources[10] = ['name' => 'Jobstreet: Yogyakarta', 'url' => 'https://www.jobstreet.co.id/id/job-search/job-vacancy.php?key=&location=32700&specialization=&area=&salary=&ojs=3&src=12'];

        return $sources;
    }

}
