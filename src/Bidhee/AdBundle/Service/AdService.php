<?php

namespace Bidhee\AdBundle\Service;

use Doctrine\ORM\EntityManager;
use Bidhee\AdBundle\Entity\Ad;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class AdService
 * @author Danepliz
 */
class AdService
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

    public function getAdListQuery($filters = [])
    {
        return $this->em->getRepository(Ad::class)->getAdListQuery($filters);
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function getAdList($filters = [])
    {
        $data['ads'] = $this->em->getRepository(Ad::class)->getAdList($filters);
        $data['pageTitle'] = 'Ad';
        $data['pageDescription'] = 'List';

        return $data;
    }

    /**
     * @param $id
     * @return Ad|null|object
     */
    public function getSpecificAd($id)
    {
        return $this->em->getRepository(Ad::class)->find($id);
    }

    /**
     * @param Ad $ad
     */
    public function create(Ad $ad)
    {
        $this->em->getRepository(Ad::class)->create($ad);
    }

    /**
     * Checks Ad Image Dimension as per Dimension mentioned in Ad Type
     *
     * @param Ad $ad
     *
     * @return bool < true on success or information message on failure >
     */
    public function checkAdImageDimension(Ad $ad)
    {
        $adDimension = $ad->getAdType();
        $requiredImageWidth = $adDimension->getWidth();
        $requiredImageHeight = $adDimension->getHeight();

        $requiredMinWidth = $requiredImageWidth - 5;
        $requiredMaxWidth = $requiredImageWidth + 5;
        $requiredMinHeight = $requiredImageHeight - 5;
        $requiredMaxHeight = $requiredImageHeight + 5;

        $imageInfo = getimagesize($ad->getFile());

        $imageWidth = $imageInfo[0];
        $imageHeight = $imageInfo[1];

        $widthCheck = ($imageWidth >= $requiredMinWidth and $imageWidth <= $requiredMaxWidth)
            ? true
            : false;

        $heightCheck = ($imageHeight >= $requiredMinHeight and $imageHeight <= $requiredMaxHeight)
            ? true
            : false;

        return ($heightCheck and $widthCheck)
            ? true
            : 'Required Dimension for Advertisement Image is ' . $requiredImageWidth . 'x' . $requiredImageHeight;

    }
}
