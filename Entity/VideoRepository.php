<?php

namespace Swm\VideotekBundle\Entity;

use Doctrine\ORM\EntityRepository;

class VideoRepository extends EntityRepository
{
    public function getLast($limit = 12)
    {
        return $this->createQueryBuilder('v')->setMaxResults($limit)->where('v.statut = 1')->orderBy('v.id', 'DESC');
    }

    public function getWaiting($limit = 12)
    {
        return $this->createQueryBuilder('v')->setMaxResults($limit)->where('v.statut = 0')->orderBy('v.id', 'DESC')->getQuery()->getResult();
    }

    public function getByHits($limit = 12)
    {
        return $this->createQueryBuilder('v')->setMaxResults($limit)->where('v.statut = 1')->orderBy('v.hits', 'DESC')->getQuery()->getResult();
    }

    public function getByTag($tag, $limit = 12)
    {
        return $this->createQueryBuilder('v')->join('v.tags', 't')->setMaxResults($limit)->where('t.id = :id')->setParameter('id', $tag)->orderBy('v.hits', 'DESC')->getQuery()->getResult();
    }

    public function getByFav($limit = 12)
    {
        return $this->createQueryBuilder('v')->setMaxResults($limit)->where('v.statut = 1')->andWhere('v.fav = 1')->orderBy('v.hits', 'DESC')->getQuery()->getResult();
    }

    public function getMore($startId = 0, $limit = 4)
    {
        $startId++;
        return $this->createQueryBuilder('v')->setMaxResults($limit)->where('v.statut = 1')
            ->where('v.id >= :startid')
            ->setParameter('startid', $startId)
            ->getQuery()->getResult();
    }

    public function getImport()
    {
        return $this->createQueryBuilder('v')->where('v.statut = 1')->setMaxResults(2)->getQuery()->getResult();
    }

    public function getByUrl($url)
    {
        return $this->createQueryBuilder('v')->where('v.url = :url')->setParameter('url', $url)->getQuery()->getResult();
    }

    public function getDoublon($url)
    {
        $videos = $this->getByUrl($url);

        return (int) count($videos);
    }

    public function getRandomVideo($limit = 1)
    {
        $nbre = $this->createQueryBuilder('v')
            ->select('COUNT(v)')
            ->getQuery()
            ->getSingleScalarResult();

        $randId = rand(1, $nbre);
        $result = $this->createQueryBuilder('v')->setMaxResults($limit)->where('v.id >= :randid')
        ->setParameter('randid', $randId)->getQuery()->getSingleResult();

        return $result;
    }
}