<?php

namespace Swm\VideotekBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Swm\VideotekBundle\Entity\Video
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Swm\VideotekBundle\Entity\VideoRepository")
 */
class Video
{
    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="videos")
     * @ORM\JoinTable(name="video_tag")
     */
    private $tags;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    private $id;

    /**
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;
    
    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;
    
    /**
     * @var string $slug
     *
     * @Gedmo\Slug(fields={"title", "id"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;    

    /**
     * @var boolean $fav
     *
     * @ORM\Column(name="fav", type="boolean")
     */
    private $fav;

    /**
     * @var boolean $statut
     *
     * @ORM\Column(name="statut", type="boolean")
     */
    private $statut;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer $hits
     *
     * @ORM\Column(name="hits", type="integer")
     */
    private $hits;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set hits
     *
     * @param integer $hits
     */
    public function setHits($hits)
    {
        $this->hits = $hits;

        return $this;
    }

    /**
     * Get hits
     *
     * @return integer 
     */
    public function getHits()
    {
        return $this->hits;
    }
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set fav
     *
     * @param boolean $fav
     */
    public function setFav($fav)
    {
        $this->fav = $fav;

        return $this;
    }

    /**
     * Get fav
     *
     * @return boolean 
     */
    public function getFav()
    {
        return $this->fav;
    }

    /**
     * Set statut
     *
     * @param boolean $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return boolean 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Add tags
     *
     * @param Tag $tags
     */
    public function addTag(Tag $tag)
    {
        if($this->tags->contains($tag)) return;
        $this->tags[] = $tag;
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getTag()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        foreach ($tags as $tag) {
            if($this->tags->contains($tag)) continue;
            $this->addTag($tag);
        }
        return $this;
    }

    public function setView()
    {
        $this->hits++;
    }
}