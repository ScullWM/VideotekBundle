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
        $formatedObject->setTitle($data->snippet->title);
        $formatedObject->setDescription($data->snippet->description);
        $formatedObject->setUrl('https://www.youtube.com/watch?v='.$videoId);
        $formatedObject->setImg($data->snippet->thumbnails->medium->url);
        $formatedObject->setService('y');
        $formatedObject->setVideoid($videoId);

        return $formatedObject;
    }
}