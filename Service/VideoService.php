<?php

namespace Swm\VideotekBundle\Service;

use Swm\VideotekBundle\Entity\Video;

class VideoService
{
    public function getInfoFromVideo(Video $video)
    {
        $youtubeId = $this->getYoutubeId($video->getUrl());
        $extended  = $this->getYoutubeInfoById($youtubeId);

        $videoExtended = new \StdClass();
        $videoExtended->videoModel = $video;
        $videoExtended->videoInfo  = $extended['video'];
        $videoExtended->img_small  = $extended['img_small'];
        $videoExtended->img_big    = $extended['img_big'];

        return $videoExtended;
    }

    private function getYoutubeId($url){
        $debut_id = explode("v=",$url,2);
        $id_et_fin_url = explode("&",$debut_id[1],2);

        return (string) $id_et_fin_url[0];
    }

    private function getYoutubeInfoById($youtubeId)
    {
        $video = '<iframe width="560" height="315" src="http://www.youtube.com/embed/'.$youtubeId.'" frameborder="0" allowfullscreen></iframe>';
        $img_small = 'http://img.youtube.com/vi/'.$youtubeId.'/0.jpg';
        $img_big   = 'http://img.youtube.com/vi/'.$youtubeId.'/0.jpg';

        $code = array('video'=>$video,'img_small'=>$img_small,'img_big'=>$img_big);
        return (array) $code;
    }
}