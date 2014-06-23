<?php

namespace Swm\VideotekBundle\Scrapper;

use Madcoda\Youtube;
use Swm\VideotekBundle\Scrapper\ModelScrapper;
use Swm\VideotekBundle\Model\VideoFromApiModel;

class YoutubeScrapper extends ModelScrapper implements VideoScrapperInterface
{
    private $youtube;

    public function __construct($key)
    {
        $this->youtube = new Youtube(array('key' => $key));
    }

    public function search($term)
    {
        $results = $this->youtube->searchVideos($term);
        $formatedResults = array_map(array($this, 'formatResult'), $results);

        return (array) $formatedResults;
    }

    public function seeResult($id)
    {
        return $this->youtube->getVideoInfo($id);
    }

    protected function formatResult($data)
    {
        $formatedObject = new VideoFromApiModel();
        $formatedObject->title = $data->snippet->title;
        $formatedObject->description = $data->snippet->description;
        $formatedObject->url = 'https://www.youtube.com/watch?v='.$data->id->videoId;
        $formatedObject->img = $data->snippet->thumbnails->default->url;
        $formatedObject->service = 'y';
        $formatedObject->videoid = $data->id->videoId;

        return $formatedObject;
    }
}