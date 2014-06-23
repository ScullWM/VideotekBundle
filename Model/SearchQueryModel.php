<?php

namespace Swm\VideotekBundle\Model;

class SearchQueryModel
{
    public $keyword;
    public $hostService;
    public $videoid;

    public function __construct($keyword = null, $hostService = 'y', $videoid = null)
    {
        $this->keyword = $keyword;
        $this->hostService = $hostService;
        $this->videoid = $videoid;
    }
}