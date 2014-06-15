<?php

namespace Swm\VideotekBundle\Service\VideoService;

class VimeoVideoService implements VideoServiceInterface
{
    /**
     * Get Video id
     *
     * @version 12-06-14
     * @param  string $url Url wanted
     * @return int      Video id
     */
    public function getVideoId($url)
    {
        $id = substr(parse_url($url, PHP_URL_PATH), 1);
        return (int) $id;
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
        $video = '<iframe src="http://player.vimeo.com/video/'.$id.'?color=ff9933" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
        $img_small = '';
        $img_big   = '';

        $code = array('video'=>$video,'img_small'=>$img_small,'img_big'=>$img_big, 'id'=>$id);
        return (array) $code;
    }
}