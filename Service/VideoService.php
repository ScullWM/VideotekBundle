<?php

namespace Swm\VideotekBundle\Service;

use Swm\VideotekBundle\Entity\Video;
use Swm\VideotekBundle\Service\VideoService\VimeoVideoService;
use Swm\VideotekBundle\Service\VideoService\YoutubeVideoService;
use Swm\VideotekBundle\Service\VideoService\DailymotionVideoService;
use Swm\VideotekBundle\Exception\VideoException;

class VideoService
{
    private $specificVideoService;

    /**
     * Get Extended Video with more informations
     *
     * @version  06-06-2014
     * @param  Video  $video [description]
     * @return object        [description]
     */
    public function getInfoFromVideo(Video $video)
    {
        switch (true) {
            case strstr($video->getUrl(),'youtu'):
                $this->specificVideoService = new YoutubeVideoService();
                break;
            case strstr($video->getUrl(),'daily'):
                $this->specificVideoService = new DailymotionVideoService();
                break;
            case strstr($video->getUrl(),'vimeo'):
                $this->specificVideoService = new VimeoVideoService();
                break;
            default:
                throw new VideoException(sprintf('No hosting service found for %s', $video->getUrl()));
        }

        $videoId   = $this->specificVideoService->getVideoId($video->getUrl());
        $extended  = $this->specificVideoService->getThumbnails($videoId);

        $videoExtended = new \StdClass();
        $videoExtended->videoModel = $video;
        $videoExtended->videoInfo  = $extended['video'];
        $videoExtended->img_small  = $extended['img_small'];
        $videoExtended->img_big    = $extended['img_big'];

        return (object) $videoExtended;
    }
}