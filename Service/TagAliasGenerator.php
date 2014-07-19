<?php

namespace Swm\VideotekBundle\Service;

use Swm\VideotekBundle\Entity\Tag;
use Swm\VideotekBundle\Model\TagAliasModel;

class TagAliasGenerator
{
    private $alias = array();

    public function process(array $list)
    {
        array_map(array($this, 'getTagAlias'), $list);
        return $this->alias;
    }

    private function getTagAlias(Tag $tag)
    {
        $tmpAlias   = array();
        $tmpAlias[] = $tag->getTag();
        $tmpAlias[] = str_replace('-','', $tag->getTag());
        $tmpAlias[] = str_replace('-',' ', $tag->getTag());
        $tmpAlias[] = str_replace(' ','-', $tag->getTag());
        $tmpAlias[] = str_replace(' ','', $tag->getTag());

        foreach ($tmpAlias as $alias) {
            $this->alias[] = $this->getAlias($alias, $tag);
        }
    }

    public function getAlias($alias, Tag $tag)
    {
        return new TagAliasModel($alias, $tag);
    }
}