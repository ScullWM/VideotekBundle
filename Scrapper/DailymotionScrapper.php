<?php

namespace Swm\VideotekBundle\Scrapper;

use Swm\VideotekBundle\Scrapper\ModelScrapper;
use Swm\VideotekBundle\Model\VideoFromApiModel;
use Swm\VideotekBundle\Adapter\GuzzleAdapter;

class DailymotionScrapper extends ModelScrapper implements VideoScrapperInterface
{
    private $dailmotionKey;
    private $searchUrl = 'https://api.dailymotion.com/videos?search=keyword&fields=id,title,description,embed_url,thumbnail_480_url';
    private $videoidUrl = 'https://api.dailymotion.com/video/videoid?fields=id,title,description,embed_url,thumbnail_480_url';

    public function __construct($key)
    {
        $this->dailmotionKey = $key;
    }

    public function search($term)
    {
        $httpclient = new GuzzleAdapter();
        
        $url = str_replace('keyword', urlencode($term), $this->searchUrl);
        $results = $httpclient->getJson($url);

        $formatedResults = array_map(array($this, 'formatResult'), $results['list']);

        return (array) $formatedResults;

    }

    public function seeResult($id)
    {
        $httpclient = new GuzzleAdapter();
        
        $url = str_replace('videoid', $id, $this->videoidUrl);
        $video = $httpclient->getJson($url);

        return $this->formatResult($video);
    }


    private function formatResult($data)
    {
        $videoId = $data['id'];

        $formatedObject = new VideoFromApiModel();
        $formatedObject->title = $data['title'];
        $formatedObject->description = $data['description'];
        $formatedObject->url = $data['embed_url'];
        $formatedObject->img = $data['thumbnail_480_url'];
        $formatedObject->service = 'd';
        $formatedObject->videoid = $videoId;

        return $formatedObject;
    }
}