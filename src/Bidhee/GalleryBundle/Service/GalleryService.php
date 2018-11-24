<?php

namespace Bidhee\GalleryBundle\Service;

use Doctrine\ORM\EntityManager;
use Bidhee\GalleryBundle\Entity\Album;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class GalleryService
 * @author Bhaktaraz Bhatta <bhattabhakta@gmail.com>
 */
class GalleryService
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
    public function __construct(EntityManager $entityManager, TokenStorageInterface $token)
    {
        $this->em = $entityManager;

        $this->token = $token;
    }

    /**
     * @param $limit
     * @return array
     */
    public function getAlbums($limit)
    {
        return $this->em->getRepository(Album::class)->getAlbums($limit);
    }

    /**
     * @param $limit
     * @return array
     */
    public function getFeaturedGalleryImage($limit)
    {
        return $this->em->getRepository(Album::class)->getFeaturedImage($limit);
    }

    /**
     * @param $limit
     * @return array
     */
    public function getImportantAlbum($limit)
    {
        return $this->em->getRepository(Album::class)->getImportantAlbum($limit);
    }

    /**
     * @param $albumId
     * @return array
     */
    public function getImagesById($albumId)
    {
        return $this->em->getRepository(Album::class)->getImagesByAlbumId($albumId);
    }
}
