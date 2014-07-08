<?php

namespace Swm\VideotekBundle\Model;

class VideoFromApiModel extends BaseModel
{
    private $title;
    private $description;
    private $url;
    private $img;
    private $service;
    private $videoid;

    /**
     * Gets the value of title.
     *
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Sets the value of title.
     *
     * @param mixed $title the title 
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the value of description.
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Sets the value of description.
     *
     * @param mixed $description the description 
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the value of url.
     *
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * Sets the value of url.
     *
     * @param mixed $url the url 
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Gets the value of img.
     *
     * @return mixed
     */
    public function getImg()
    {
        return $this->img;
    }
    
    /**
     * Sets the value of img.
     *
     * @param mixed $img the img 
     *
     * @return self
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Gets the value of service.
     *
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }
    
    /**
     * Sets the value of service.
     *
     * @param mixed $service the service 
     *
     * @return self
     */
    public function setService($service)
    {
        $this->service = $service;

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