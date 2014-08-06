<?php

namespace Swm\VideotekBundle\Service;

class TagTransform
{
    private $tags = array();

    public function process($txt)
    {
        $txtline = explode("\n", $txt);
        $txtline = array_map('trim', $txtline);

        array_map(array($this, 'extractSubTag'), $txtline);

        $this->tags = array_filter($this->tags);
        $this->tags = array_unique($this->tags);

        $this->deleteBadTag();

        return $this->tags;
    }

    private function deleteBadTag()
    {
        foreach ($this->tags as $keytag=>$tag) {
            if(strlen($tag)<=2) unset($this->tags[$keytag]);
            if(is_numeric($tag)) unset($this->tags[$keytag]);
        }
    }


    private function extractSubTag($string)
    {
        $string = str_replace('  ', ' ', $string);
        if(true === (bool)preg_match('~[0-9]~', $string))
        {
            $this->getTagWithNumber($string);
        }else {
            $this->tags[] = trim($string);
        }
    }

    private function getTagWithNumber($string)
    {
        $tmpTag = explode(' ', $string);

        $intTag = null;
        foreach ($tmpTag as $tmp) {
            if(true === (bool)preg_match('~[0-9]~', $tmp)) $intTag = $tmp;
        }

        $string = str_replace($intTag, '', $string);
        if(!empty($intTag)) $this->tags[] = trim($intTag);
        $this->tags[] = trim($string);
    }
}