<?php

namespace Swm\VideotekBundle\Service\VideoService;

class DailymotionVideoService implements VideoServiceInterface
{
    /**
     * Get Video id
     *
     * @version 12-06-14
     * @param  string $url Url wanted
     * @return string      Video id
     */
    public function getVideoId($url)
    {
        $id = strtok(basename($url), '_');
        return (string) $id;
    }

    /**
     * Get thumbnails and iframe code from an id
     *
     * @version 12-06-14
     * @param  string  $id   Video id
     * @return array   $code List of output information
     */
    public function getThumbnails($id)
    {
        $video = '<iframe frameborder="0" src="http://www.dailymotion.com/embed/video/'.$id.'"></iframe>';
        $img_small = 'http://www.dailymotion.com/thumbnail/160x120/video/'.$id;
        $img_big   = 'http://www.dailymotion.com/thumbnail/160x120/video/'.$id;

        $code = array('video'=>$video,'img_small'=>$img_small,'img_big'=>$img_big, 'id'=>$id);
        return (array) $code;
    }
}