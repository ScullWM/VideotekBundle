<?php

namespace Swm\VideotekBundle\Scrapper;

use Madcoda\Youtube;

class YoutubeScrapper implements VideoScrapperInterface
{
    private $youtube;

    public function __construct($key)
    {
        $this->youtube = new Youtube(array('key' => $key));
    }

    public function search($term)
    {
        return $this->youtube->searchVideos($term);
    }

    public function seeResult($id)
    {
        return $this->youtube->getVideoInfo($id);
    }
}