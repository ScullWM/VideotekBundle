<?php

namespace Swm\VideotekBundle\Model;

use Eko\FeedBundle\Item\Writer\RoutedItemInterface;

class VideoExtended implements RoutedItemInterface
{
    public $id;
    public $videoModel;
    public $videoInfo;
    public $img_small;
    public $img_big;

    public function __construct($video, $videoId, $extended)
    {
        $this->id         = $videoId;
        $this->videoModel = $video;
        $this->videoInfo  = $extended['video'];
        $this->img_small  = $extended['img_small'];
        $this->img_big    = $extended['img_big'];
    }

    public function getFeedItemTitle()
    {
        return $this->videoModel->getTitle();
    }

    public function getFeedItemDescription()
    {
        $description = $this->convertTags($this->videoModel->getTags());

        return $description;
    }

    public function getFeedItemPubDate()
    {
        return new \DateTime();
    }

    public function getFeedItemRouteName()
    {
        return 'video_info';
    }

    public function getFeedItemRouteParameters()
    {
        return array('id'=>$this->videoModel->getId());
    }

    public function getFeedItemUrlAnchor()
    {
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

    private function myTruncate($string, $limit, $break=".", $pad="...") {
        // return with no change if string is shorter than $limit
        if(strlen($string) <= $limit)
            return $string;

        // is $break present between $limit and the end of the string?
        if(false !== ($breakpoint = strpos($string, $break, $limit))) {
            if($breakpoint < strlen($string) - 1) {
                $string = substr($string, 0, $breakpoint) . $pad;
            }
        }
        return $string;
    }
}