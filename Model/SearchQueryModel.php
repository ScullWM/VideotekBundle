<?php

namespace Swm\VideotekBundle\Model;

class SearchQueryModel
{
    private $keyword;
    private $hostService;

    public function __construct($keyword = null, $hostService = 'y')
    {
        $this->keyword = $keyword;
        $this->hostService = $hostService;
    }
}