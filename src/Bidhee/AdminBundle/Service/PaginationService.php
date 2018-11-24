<?php

namespace Bidhee\AdminBundle\Service;


use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class PaginationService
 * @author Danepliz
 */
class PaginationService
{

    /**
     * @var ContainerInterface
     */
    private $container;


    /**
     * PaginationService constructor.
     * @param ContainerInterface $containerInterface
     */
    public function __construct(ContainerInterface $containerInterface)
    {
        $this->container = $containerInterface;
    }

    /**
     * @param Query $query
     * @param int $limit
     * @param int $page
     * @return mixed
     */
    public function getPaginatedList(Query $query, $limit = 0, $page = 0)
    {
        $paginator = $this->container->get('knp_paginator');

        $page = ( $page ==  0 )
            ? $this->container->get('request')->query->getInt('page', 1)
            : $page;

        $limit = ( $limit == 0 )
            ? $this->container->getParameter('per_page_limit')
            : $limit;

        return $paginator->paginate(
            $query,
            $page,
            $limit
        );
    }

}