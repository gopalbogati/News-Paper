<?php

namespace Bidhee\AdBundle\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityRepository;
use Bidhee\AdBundle\Entity\AdType;

/**
 * AdTypeRepository
 *
 * @author Danepliz
 */
class AdTypeRepository extends EntityRepository
{

    /**
     * @param array $filters
     * @return array
     */
    public function getAdTypesList($filters = [])
    {
        $query = $this->getAdTypesListQuery($filters);

        return $query->getResult();

    }

    /**
     * @param array $filters
     * @return Query
     */
    public function getAdTypesListQuery($filters = [])
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('a')
            ->from(AdType::class, 'a')
            ->where('1=1')
        ;

        $qb->orderBy('a.createdOn','DESC');

        return $qb->getQuery();


    }

    /**
     * @param AdType $adType
     */
    public function create(AdType $adType)
    {
        $this->_em->persist($adType);
        $this->_em->flush();
    }

}
