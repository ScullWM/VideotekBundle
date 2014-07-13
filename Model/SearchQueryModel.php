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

    /**
     * Gets the value of keyword.
     *
     * @return mixed
     */
    public function getKeyword()
    {
        return $this->keyword;
    }
    
    /**
     * Sets the value of keyword.
     *
     * @param mixed $keyword the keyword 
     *
     * @return self
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * Gets the value of hostService.
     *
     * @return mixed
     */
    public function getHostService()
    {
        return $this->hostService;
    }
    
    /**
     * Sets the value of hostService.
     *
     * @param mixed $hostService the host service 
     *
     * @return self
     */
    public function setHostService($hostService)
    {
        $this->hostService = $hostService;

        return $this;
    }

    /**
     * Gets the value of videoid.
     *
     * @return mixed
     */
    public function getVideoid()
    {
        return $this->videoid;
    }
    
    /**
     * Sets the value of videoid.
     *
     * @param mixed $videoid the videoid 
     *
     * @return self
     */
    public function setVideoid($videoid)
    {
        $this->videoid = $videoid;

        return $this;
    }
}