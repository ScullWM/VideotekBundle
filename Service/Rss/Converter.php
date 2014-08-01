<?php

namespace Swm\VideotekBundle\Service\Rss;

use Swm\VideotekBundle\Entity\Video;

class Converter
{
    public function convert(array $videos)
    {
        return array_map(array($this, 'convertVideo'), $videos);
    }

    private function convertVideo(Video $video)
    {
        $videoRss = array();
        $videoRss['title'] = $video->getTitle();
        $videoRss['tags'] = $this->convertTags($video->getTags());

        return $videoRss;
    }

    private function convertTags($tags)
    {
        $str = null;

        foreach ($tags as $tag) {
            $str .= ' #'.$tag->getTag();
        }

        return (string) $str;
    }
}