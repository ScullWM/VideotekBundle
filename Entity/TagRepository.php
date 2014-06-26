<?php

namespace Swm\VideotekBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    public function getAll($limit = 100)
    {
        return $this->createQueryBuilder('t')->setMaxResults($limit)->getQuery()->getResult();
    }
}