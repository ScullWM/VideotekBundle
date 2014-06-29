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
        $video = $this->youtube->getVideoInfo($id);

        return $this->formatResult($video);
    }

    /**
     * @param \stdClass $data
     */
    protected function formatResult($data)
    {
        $videoId = (isset($data->id) && is_string($data->id))?$data->id:$data->id->videoId;

        $formatedObject = new VideoFromApiModel();
        $formatedObject->title = $data->snippet->title;
        $formatedObject->description = $data->snippet->description;
        $formatedObject->url = 'https://www.youtube.com/watch?v='.$videoId;
        $formatedObject->img = $data->snippet->thumbnails->medium->url;
        $formatedObject->service = 'y';
        $formatedObject->videoid = $videoId;

        return $formatedObject;
    }
}