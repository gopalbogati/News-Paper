<?php

namespace Bidhee\GalleryBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Bidhee\GalleryBundle\Entity\Album;
use Bidhee\GalleryBundle\Entity\Image;

/**
 * AlbumRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AlbumRepository extends EntityRepository
{


    public function getModelsListQuery($filters = [])
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('a')
            ->from(Album::class, 'a');

        if (array_key_exists('name', $filters) and $filters['name'] != '') {
            $qb->andWhere('a.name LIKE :name')->setParameter('name', '%' . $filters['name'] . '%');
        }


        if (array_key_exists('album_type', $filters) and $filters['album_type'] != '') {
            $qb->andWhere('a.id = :album_type')->setParameter('album_type', $filters['album_type']);
        }

        if (array_key_exists('status', $filters) and $filters['status'] != '') {
            $qb->andWhere('a.status = :status')->setParameter('status', $filters['status']);
        }

        $qb->orderBy('a.createdOn', 'DESC');

        return $qb->getQuery();

    }

    /**
     * @param $limit
     * @return array
     */
    public function getAlbums($limit)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('n')
            ->from(Album::class, 'n')
            ->where('n.status = 1')
            ->orderBy('n.id', 'DESC');

        $qb->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $limit
     * @return array
     */
    public function getFeaturedImage($limit)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('a')
            ->from(Album::class, 'a')
            ->where('a.status = 1')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('a.isImportantNews', ':isImportantNews')
            ))
            ->setParameters([
                'isImportantNews' => true
            ])
            ->orderBy('a.createdOn', 'DESC');

        $qb->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $limit
     * @return array
     */
    public function getImportantAlbum($limit)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('a')
            ->from(Album::class, 'a')
            ->where('a.status = 1')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('a.isImportantNews', ':isImportantNews')
            ))
            ->setParameters([
                'isImportantNews' => false
            ])
            ->orderBy('a.createdOn', 'DESC');

        $qb->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $albumId
     * @return array
     */
    public function getImagesByAlbumId($albumId)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('i')
            ->from(Image::class, 'i')
            ->where('i.album=:aid')
            ->setParameter('aid', $albumId)
            ->orderBy('i.id', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
