<?php

namespace Swm\VideotekBundle\Service;

class SearchQuery
{
    public $keyword;
    public $hostService;

    public function __construct($keyword = null, $hostService = 'y')
    {
        $this->keyword = $keyword;
        $this->hostService = $hostService;
    }
}