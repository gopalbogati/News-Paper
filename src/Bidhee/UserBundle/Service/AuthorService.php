<?php

namespace Bidhee\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use Bidhee\UserBundle\Entity\Author;

/**
 * Class AuthorService
 * @package Bidhee\UserBundle\Service
 * @author Deepak Pandey
 */
class AuthorService
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
    public function getAuthorListQuery($filters = [])
    {
        return $this->em->getRepository(Author::class)->getAuthorListQuery($filters);
    }

    /**
     * @param int $authorId
     * @return Author
     */
    public function getAuthor($authorId)
    {
        return $this->em->getRepository(Author::class)->find($authorId);
    }
}
