<?php

namespace Swm\VideotekBundle\Model;

use Swm\VideotekBundle\Entity\Tag;

class TagAliasModel extends BaseModel
{
    private $alias;
    private $tag;

    public function __construct($alias, Tag $tag)
    {
        $this->alias = $this->alias;
        $this->tag = $tag;
    }

    /**
     * Gets the value of alias.
     *
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }
    
    /**
     * Gets the value of tag.
     *
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag->getTag();
    }

    public function getOriginalTag()
    {
        return $this->tag;
    }
}