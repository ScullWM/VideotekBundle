<?php

namespace Swm\VideotekBundle\Scrapper;

use Swm\VideotekBundle\Scrapper\YoutubeScrapper;
use Swm\VideotekBundle\Scrapper\ViemoScrapper;
use Swm\VideotekBundle\Scrapper\DailymotionScrapper;

class VideoScrapper 
{
    private $request;
    private $scrapperService;

    public function __construct(Request $request)
    {
        $this->request = $request;
        switch ($this->request->get('serviceform')) {
            case 'y':
                $this->scrapperService = new YoutubeScrapper();
                break;
            case 'd':
                $this->scrapperService = new DailymotionScrapper();
                break;
            case 'v':
                $this->scrapperService = new VimeoScrapper();
                break;
            default:
                throw new ScrapperException('No hosting scrapper found');
        }
    }
}