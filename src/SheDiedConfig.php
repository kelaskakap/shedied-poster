<?php

namespace SheDied;

use SheDied\helpers\Dua;
use SheDied\helpers\Satu;
use SheDied\helpers\Tiga;

class SheDieDConfig {

    static $sources = [];

    /**
     * SITE DOMAIN adalah project yang aktif
     */
    const SITE_DOMAIN = 'awesomedecors.us';
    const AUTHOR_ID = 1;
    const BOT_POST_INVTERVAL = 2; //minutes

    private static function _sources() {

        if (empty(self::$sources)) {

            if (self::SITE_DOMAIN == Satu::LOKERKREASI_COM)
                self::$sources = Satu::sources();
            elseif (self::SITE_DOMAIN == Dua::AWESOMEDECORS_US)
                self::$sources = Dua::sources();
            elseif (self::SITE_DOMAIN == Tiga::POJOKJOGJA_COM)
                self::$sources = Tiga::sources();
        }
    }

    public static function getSourcesList() {

        self::_sources();
        return self::$sources;
    }

    public static function getSource($id) {

        self::_sources();
        if (array_key_exists($id, self::$sources)) {
            return self::$sources[$id];
        } else {
            return FALSE;
        }
    }

    static public function pick_Sources(Array $indexes, Array $cats) {

        if (count($indexes) != count($cats))
            return;

        $x = [];

        foreach ($indexes as $key => $val) {

            $t = self::getSource($val);

            if ($t) {

                $t['cat'] = $cats[$key];
                $x[$val] = $t;
            }
        }

        return $x;
    }

}
