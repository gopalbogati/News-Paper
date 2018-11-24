<?php

namespace Bidhee\AdBundle\Service;

use Doctrine\ORM\EntityManager;
use Bidhee\AdBundle\Entity\AdType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class AdTypeService
 * @author Danepliz
 */
class AdTypeService
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var TokenStorageInterface
     */
    private $token;

    /**
     * NewsService constructor.
     * @param EntityManager $entityManager
     * @param TokenStorageInterface $token
     */
    public function __construct(EntityManager $entityManager,TokenStorageInterface $token)
    {
        $this->em = $entityManager;

        $this->token = $token;
    }

    public function getAdTypesListQuery( $filters = [] )
    {
        return $this->em->getRepository(AdType::class)->getAdTypesListQuery($filters);
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function getAdTypesList($filters = [])
    {
        $data['adTypes'] = $this->em->getRepository(AdType::class)->getAdTypesList($filters);
        $data['pageTitle'] = 'Ad Types';
        $data['pageDescription'] = 'List';

        return $data;
    }

    /**
     * @param $id
     * @return AdType|null|object
     */
    public function getSpecificAdType($id)
    {
        return $this->em->getRepository(AdType::class)->find($id);
    }

    /**
     * @param AdType $adType
     */
    public function create(AdType $adType)
    {
        $this->em->getRepository(AdType::class)->create($adType);
    }
}
