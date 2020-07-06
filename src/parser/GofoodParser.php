<?php

namespace SheDied\parser;

use SheDied\parser\AbstractParser;

class GofoodParser extends AbstractParser
{

    const GOFOOD_ADDR = 'https://gofood.co.id';

    protected $address;
    protected $phone;
    protected $partner;
    protected $operations = [];
    protected $rating;
    protected $longlat;

    protected function getPostDetail()
    {

        $this->address = $this->link_content->address;
        $this->phone = $this->link_content->phone_number;
        $this->partner = $this->link_content->partner;
        $this->rating = $this->link_content->rating->text;
        $this->longlat = $this->link_content->location;

        foreach ($this->link_content->multi_operational_hours as $h)
        {

            $day = $this->dayOfWeek($h->day_of_week);
            $this->operations[$day] = $h->timings[0];
        }
    }

    public function grab()
    {

        $this->getPostDetail();
        $this->_getFeaturedImage();
        $this->_getTags();
        $this->generateSeoMetaDescription();
        $this->generateSeoMetaTitle();
        $this->renderHtml();
    }

    protected function _getFeaturedImage()
    {

        $this->featured_image = $this->link_content->image_url;
    }

    protected function renderHtml()
    {

        $html = '<div class="gofood">';

        //badges
        $html .= '<div class="gf-badges row">';
        $html .= '<div class="col-md-4 tags">';
        if ($this->tags)
            $html .= implode(', ', $this->tags);
        $html .= '</div>';
        $html .= '<div class="col-md-4 partner">';
        if ($this->partner)
            $html .= '<img src="/wp-content/uploads/2020/04/badge-partner-v2.png" width="auto" height="24px" >';
        $html .= '</div>';
        $html .= '<div class="col-md-4 rating">';
        $html .= '<img src="/wp-content/uploads/2020/04/list-rating-star-icon.png" width="auto" height="24px">';
        $html .= '<span>' . $this->rating . '</span>';
        $html .= '</div>';
        $html .= '</div>';
        //end badges
        //
        //
        //address + phone
        $html .= '<div class="gf-address row">';
        $html .= '<div class="col-md-8 address">';
        $html .= '<div class="alamat">';
        $html .= 'Alamat :';
        $html .= '</div>';
        $html .= '<div class="isialamat">';
        $html .= $this->address;
        $html .= '</div>';
        if ($this->longlat)
            $html .= '<a href="https://maps.google.com/?q=' . $this->longlat . '" target="_blank" rel="noopener noreferrer" class="location">Lihat peta</a>';
        $html .= '</div>';
        $html .= '<div class="col-md-4 phone">';
        $html .= '<div class="telepon">';
        $html .= 'Nomor telepon :';
        $html .= '</div>';
        $html .= '<div class="isitelepon">';
        $html .= $this->phone;
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        //end address
        //
        //
        //
        // jam buka
        $html .= '<div class="gf-operations"><h3>Jam Operasional</h3><ul>';
        foreach ($this->operations as $day => $time)
        {

            $html .= '<li>' . $day . ' : ' . $time->open_time . ' - ' . $time->close_time . '</li>';
        }
        $html .= '</ul></div>';
        // end jam buka
        // 
        // 
        // 
        // sources
        $html .= '<div class="gf-sources">';
        $html .= '<div class="row satu">';
        $html .= '<div class="col-md-12 head">';
        $html .= '<p class="head-1">Makanan favorit dan hasil kulineran baru kamu cus langsung dianter</p>';
        $html .= '<p class="head-2">Download Gojek dan buka GoFood buat nikmatin layanannya.</p>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="row dua">';
        $html .= '<div class="col-md-4 linksource">';
        $html .= '<ul class="galepro-core-socialicon-share">
<li class="whatsapp"><a class="galepro-sharebtn galepro-whatsapp" href="' . $this->url . '" target="_blank" rel="noopener noreferrer">Sumber Iklan : Gofood.co.id</a></li>
</ul>';
        $html .= '</div>';
        $html .= '<div class="col-md-4 linkandroid">';
        $html .= '<a href="https://play.google.com/store/apps/details?id=com.gojek.app" target="_blank" rel="noopener noreferrer" class="jsx-3142419457 h-100 mr-3"><button class="jsx-3142419457 btn btn_background p-0 w-100"><img alt="Get GoFood App from Google Play Market" src="https://gofood.co.id/static/images/svg/google_play_id.svg" class="jsx-3142419457" style="height: 2.9rem;"></button></a>';
        $html .= '</div>';
        $html .= '<div class="col-md-4 linkios">';
        $html .= '<a href="https://apps.apple.com/id/app/go-jek/id944875099" target="_blank" rel="noopener noreferrer" class="jsx-3142419457 h-100"><button class="jsx-3142419457 btn btn_background p-0 w-100"><img alt="Get GoFood App from App Store Market" src="https://gofood.co.id/static/images/svg/app_store_id.svg" class="jsx-3142419457" style="height: 2.9rem;"></button></a>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '</div>';
        $this->content = $html;
    }

    protected function _getTags()
    {

        foreach ($this->link_content->cuisines as $c)
        {

            $this->tags[] = $c->text;
        }
    }

    protected function generateSeoMetaDescription()
    {

        $this->meta_description = "{$this->title}. {$this->address}. Phone: {$this->phone}";
    }

    protected function generateSeoMetaTitle()
    {

        $this->meta_title = "{$this->title} - {$this->phone}";
    }

    protected function dayOfWeek($day)
    {

        if ($day == 1)
            return 'Senin';
        if ($day == 2)
            return 'Selasa';
        if ($day == 3)
            return 'Rabu';
        if ($day == 4)
            return 'Kamis';
        if ($day == 5)
            return 'Jum\'at';
        if ($day == 6)
            return 'Sabtu';
        if ($day == 0)
            return 'Minggu';

        return $day;
    }

    static public function make_URL($id)
    {

        return self::GOFOOD_ADDR . '/jakarta/restaurant/' . $id;
    }

    static public function make_Title($title)
    {

        return "Kuliner {$title}";
    }

}
