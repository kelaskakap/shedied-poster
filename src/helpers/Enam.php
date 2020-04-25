<?php

namespace SheDied\helpers;

use SheDied\helpers\Numbers;
use SheDied\PojokJogjaController;
use SheDied\parser\GofoodParser;

class Enam extends Numbers {

    const NGEMIE_COM = 'ngemie.com';

    protected $gofood_param = [];

    public function fetchCustomUrls(PojokJogjaController $controller) {
        ;
    }

    public function scanURL(PojokJogjaController $controller, $params = array()) {

        $this->query_param = $params;

        if ($this->source_Gofood($controller)) {
            $this->Gofood_ParamQuery($controller);
        }
    }

    protected function Gofood_ParamQuery(PojokJogjaController $controller) {

        if (isset($this->query_param[$controller->getNewsSrc()]))
            $this->gofood_param = $this->query_param[$controller->getNewsSrc()];

        if (empty($this->gofood_param['collection']))
            $this->gofood_param['collection'] = 'NEAR_ME';
        if (empty($this->gofood_param['search_id']))
            $this->gofood_param['search_id'] = 'e99baf24-08fc-4b36-a52b-494a0a8e1d29';
        if (empty($this->gofood_param['date']))
            $this->gofood_param['date'] = time();
        if (empty($this->gofood_param['page']))
            $this->gofood_param['page'] = 1;

        $url = $controller->getUrl();
        $url = "$url&" . http_build_query($this->gofood_param);
        $controller->setUrl($url);
    }

    public function switchParsers(PojokJogjaController $controller) {

        if ($this->source_Gofood($controller))
            $this->parser = 'SheDied\parser\GofoodParser';
    }

    public function fetchPostLinks(PojokJogjaController $controller) {

        $doc = $this->fetchLinks($controller->getUrl());
        $postlinks = $controller->getPostLinks();

        if ($this->source_Gofood($controller)) {

            $doc = json_decode($doc);

            $this->parseGoofod_Api_Response($doc, $controller, $postlinks);
        }
    }

    static public function sources() {

        $sources = self::sources_gofood();
        return $sources;
    }

    static protected function sources_gofood() {

        $sources[1] = ['name' => 'Gofood > Yogyakarta', 'url' => 'https://gofood.co.id/gofood/web/v1/restaurants?location=-7.797068,110.370529'];
        $sources[2] = ['name' => 'Gofood > Surabaya', 'url' => 'https://gofood.co.id/gofood/web/v1/restaurants?location=-7.250445,112.768845'];
        $sources[3] = ['name' => 'Gofood > Semarang', 'url' => 'https://gofood.co.id/gofood/web/v1/restaurants?location=-6.966667,110.416664'];
        $sources[4] = ['name' => 'Gofood > Jakarta', 'url' => 'https://gofood.co.id/gofood/web/v1/restaurants?location=-6.200000,106.816666'];
        $sources[5] = ['name' => 'Gofood > Bandung', 'url' => 'https://gofood.co.id/gofood/web/v1/restaurants?location=-6.914744,107.609810'];
        $sources[6] = ['name' => 'Gofood > Makassar', 'url' => 'https://gofood.co.id/gofood/web/v1/restaurants?location=-5.135399,119.423790'];
        $sources[7] = ['name' => 'Gofood > Medan', 'url' => 'https://gofood.co.id/gofood/web/v1/restaurants?location=3.597031,98.678513'];
        $sources[8] = ['name' => 'Gofood > Bali', 'url' => 'https://gofood.co.id/gofood/web/v1/restaurants?location=-8.650000,115.216667'];
        $sources[9] = ['name' => 'Gofood > Palembang', 'url' => 'https://gofood.co.id/gofood/web/v1/restaurants?location=-2.990934,104.756554'];
        $sources[10] = ['name' => 'Gofood > Pontianak', 'url' => 'https://gofood.co.id/gofood/web/v1/restaurants?location=0.000000,109.333336'];
        $sources[11] = ['name' => 'Gofood > Malang', 'url' => 'https://gofood.co.id/gofood/web/v1/restaurants?location=-7.830759,112.697098'];

        return $sources;
    }

    protected function source_Gofood(PojokJogjaController $ctrl) {

        return $ctrl->getNewsSrc() >= 1 && $ctrl->getNewsSrc() < 12;
    }

    public function getIdentity() {

        return static::NGEMIE_COM;
    }

    protected function parseGoofod_Api_Response($doc, PojokJogjaController $controller, $postlinks) {

        if ((bool) $doc->success) {

            if ($doc->next_page) {

                //parse next page
                $b = parse_url($doc->next_page, PHP_URL_QUERY);
                parse_str($b, $param);
            }

            foreach ($doc->data->cards as $card) {

                $postlinks[] = [
                    "title" => GofoodParser::make_Title($card->content->title),
                    "link" => GofoodParser::make_URL($card->content->id),
                    'src' => $controller->getNewsSrc(),
                    'cat' => $controller->getCategory(),
                    'content' => $card->content
                ];

                if ($this->enough($postlinks, $controller) && !$controller->auto()) {

                    break;
                }
            }

            $controller->setPostLinks($postlinks);
        }
    }

}
