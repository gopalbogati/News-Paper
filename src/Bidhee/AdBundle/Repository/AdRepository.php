<?php

namespace Bidhee\AdBundle\Repository;

use Doctrine\ORM\Query;
use Bidhee\AdBundle\Entity\Ad;
use Doctrine\ORM\EntityRepository;

/**
 * AdRepository
 *
 * @author Danepliz
 */
class AdRepository extends EntityRepository
{

    /**
     * @param $placement
     * @param null $limit
     * @return array
     */
    public function getAds($placement, $limit = null)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select("a")
            ->from("BidheeAdBundle:Ad", "a")
            ->where("a.publish = 1")
            ->andWhere("a.placement = :placement")
            ->andWhere("a.startDate < CURRENT_DATE()")
            ->andWhere("a.expiryDate > CURRENT_DATE()")
            ->setParameter("placement", $placement)
            ->orderBy('a.orderBy', "ASC");

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }


    /**
     * @param array $filters
     * @return array
     */
    public function getAdList($filters = [])
    {
        $query = $this->getAdListQuery($filters);

        return $query->getResult();

    }

    /**
     * @param array $filters
     * @return Query
     */
    public function getAdListQuery($filters = [])
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('a')
            ->from(Ad::class, 'a')
            ->where('1=1');

        $qb->orderBy('a.orderBy', 'ASC');
        $qb->orderBy('a.placement', 'ASC');

        return $qb->getQuery();


    }

    /**
     * @param Ad $ad
     */
    public function create(Ad $ad)
    {
        $this->_em->persist($ad);
        $this->_em->flush();
    }

}
