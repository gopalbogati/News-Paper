<?php

namespace Bidhee\EpaperBundle\Service;

use Doctrine\ORM\EntityManager;
use Bidhee\EpaperBundle\Entity\Epaper;

/**
 * Class EpaperService
 * @package Bidhee\EpaperBundle\Service
 * @author Deepak Pandey
 */
class EpaperService
{

    protected $em;


    /**
     * EpaperService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param array $filters
     * @return \Doctrine\ORM\Query
     */
    public function getEpaperListQuery($filters = [])
    {
        return $this->em->getRepository(Epaper::class)->getEpaperListQuery($filters);
    }

    /**
     * @param int $authorId
     * @return Author
     */
    public function getEpaper($epaperId)
    {
        return $this->em->getRepository(Epaper::class)->find($epaperId);
    }
}
