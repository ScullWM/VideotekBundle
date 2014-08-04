<?php

namespace Swm\VideotekBundle\Service\Rss;

use Swm\VideotekBundle\Entity\Video;

class Converter
{
    private $router;

    public function convert(array $videos)
    {
        return array_map(array($this, 'convertVideo'), $videos);
    }

    private function convertVideo(Video $video)
    {
        $videoRss          = array();
        $videoRss['title'] = $video->getTitle();
        $videoRss['tags']  = $this->convertTags($video->getTags());
        $videoRss['url']   = $this->router->generate('video_info', array('id'=>$video->getId()), true);

        return $videoRss;
    }

    private function convertTags($tags)
    {
        $str = null;

        if($tags->count() > 3) {
            $tags = $tags->slice(0, 3);
        }

        foreach ($tags as $tag) {
            $str .= ' #'.$tag->getTag();
        }

        return (string) $str;
    }

    public function setRouter($router)
    {
        $this->router = $router;
    }
}