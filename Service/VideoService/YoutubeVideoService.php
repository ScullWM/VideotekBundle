<?php

namespace Swm\VideotekBundle\Service\VideoService;

class YoutubeVideoService implements VideoServiceInterface
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
        $url_string = parse_url($url, PHP_URL_QUERY);
        parse_str($url_string, $args);
        return (string) isset($args['v']) ? $args['v'] : false;
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
        $video = '<iframe src="http://www.youtube.com/embed/'.$id.'" frameborder="0" allowfullscreen></iframe>';
        $img_small = 'http://img.youtube.com/vi/'.$id.'/0.jpg';
        $img_big   = 'http://img.youtube.com/vi/'.$id.'/0.jpg';

        $code = array('video'=>$video,'img_small'=>$img_small,'img_big'=>$img_big);
        return (array) $code;
    }
}