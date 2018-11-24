<?php

namespace Bidhee\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use Bidhee\UserBundle\Entity\Consumer;

/**
 * Class ConsumerService
 * @package Bidhee\UserBundle\Service
 * @author Bhaktaraz Bhatta <bhattabhakta@gmail.com>
 */
class ConsumerService
{

    protected $em;


    /**
     * UserService constructor.
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
    public function getConsumerListQuery($filters = [])
    {
        return $this->em->getRepository(Consumer::class)->getConsumerListQuery($filters);
    }
}
