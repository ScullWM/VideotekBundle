<?php

namespace Swm\VideotekBundle\Entity;

use Doctrine\ORM\EntityRepository;

class VideoRepository extends EntityRepository
{
    public function getLast($limit = 12)
    {
        return $this->createQueryBuilder('v')->setMaxResults($limit)->where('v.statut = 1')->orderBy('v.id', 'DESC')->getQuery()->getResult();
    }

    public function getByHits($limit = 12)
    {
        return $this->createQueryBuilder('v')->setMaxResults($limit)->where('v.statut = 1')->orderBy('v.hits', 'DESC')->getQuery()->getResult();
    }

    public function getByTag($tag, $limit = 12)
    {
        return $this->createQueryBuilder('v')->join('v.tags', 't')->setMaxResults($limit)->where($qb->expr()->in('t.id', $id))->orderBy('v.hits', 'DESC')->getQuery()->getResult();
    }

    public function getByFav($limit = 12)
    {
        return $this->createQueryBuilder('v')->setMaxResults($limit)->where('v.statut = 1')->andWhere('v.fav = 1')->orderBy('v.hits', 'DESC')->getQuery()->getResult();
    }

    public function getMore($startId = 0, $limit = 4)
    {
        return $this->createQueryBuilder('v')->setMaxResults($limit)->where('v.statut = 1')->setFirstResult($startId)->getQuery()->getResult();
    }
}