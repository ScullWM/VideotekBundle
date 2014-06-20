<?php

namespace Swm\VideotekBundle\Scrapper;

use Swm\VideotekBundle\Scrapper\YoutubeScrapper;
use Swm\VideotekBundle\Scrapper\VimeoScrapper;
use Swm\VideotekBundle\Scrapper\DailymotionScrapper;
use Swm\VideotekBundle\Scrapper\VideoScrapper;
use Swm\VideotekBundle\Exception\ScrapperException;

class VideoScrapper 
{
    private $scrapperService;
    private $youtubeKey = null;
    private $dailymotionKey = null;
    private $viemoKey = null;

    public function __construct($service)
    {
        switch ($service) {
            case 'y':
                $scrapperService = new YoutubeScrapper($youtubeKey);
                break;
            case 'd':
                $scrapperService = new DailymotionScrapper($dailymotionKey);
                break;
            case 'v':
                $scrapperService = new VimeoScrapper($vimeoKey);
                break;
            default:
                throw new ScrapperException('No hosting scrapper found');
        }
        $this->scrapperService = $scrapperService;
    }

    public function search($term)
    {
        return $this->scrapperService->search($term);
    }

    public function seeResult($id)
    {
        return $this->scrapperService->seeResult($id);
    }

    public function setYoutubeKey($key)
    {
        $this->youtubeKey = $key;
    }

    public function setDailymotionKey($key)
    {
        $this->dailymotionKey = $key;
    }

    public function setVimeoKey($key)
    {
        $this->vimeoKey = $key;
    }
}