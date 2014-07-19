<?php

namespace Swm\VideotekBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    public function getAll($limit = 100)
    {
        return $this->createQueryBuilder('t')->setMaxResults($limit)->getQuery()->getResult();
    }

    public function getByTag($stringTag)
    {
        return $this->createQueryBuilder('t')->where('t.tag = :tag')
        ->setParameter('tag', $stringTag)->getQuery()->getResult();
    }

    public function getRandomTag($limit = 1)
    {
        $nbre = $this->createQueryBuilder('t')
            ->select('COUNT(t)')
            ->getQuery()
            ->getSingleScalarResult();

        $randId = rand(1, $nbre);
        $result = $this->createQueryBuilder('t')->setMaxResults($limit)->where('t.id >= :randid')
        ->setParameter('randid', $randId)->getQuery()->getSingleResult();

        return $result;
    }
}