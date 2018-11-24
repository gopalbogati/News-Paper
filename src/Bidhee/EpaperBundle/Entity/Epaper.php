<?php

namespace Bidhee\EpaperBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Expose;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Bidhee\FoundationBundle\Traits\CreatedUpdateOnTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Author Bhaktaraz Bhatta
 *
 * @ORM\Table(name="ng_epaper")
 * @ORM\Entity(repositoryClass="Bidhee\EpaperBundle\Repository\EpaperRepository")
 * @Vich\Uploadable
 * @ExclusionPolicy("all")
 */
class Epaper
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Expose
     */
    private $title;

    /**
     * @var datetime $publishedOn
     *
     * @ORM\Column(name="published_on", type="string", nullable=false)
     */
    private $publishedOn;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="cover_image", fileNameProperty="coverImage")
     *
     * @var File
     */
    private $coverImageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $coverImage;


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Assert\File(
     *     maxSize = "6024k",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Please upload a valid PDF"
     * )
     *
     * @Vich\UploadableField(mapping="epaper_file", fileNameProperty="epaper")
     *
     * @var File
     *
     */

    private $epaperFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $epaper;

    use CreatedUpdateOnTrait;

    public function __toString()
    {
        return $this->title;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tilet
     *
     * @param string $title
     *
     * @return Author
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return datetime
     */
    public function getPublishedOn()
    {
        return $this->publishedOn;
    }

    /**
     * @param datetime $publishedOn
     * @return $this;
     */
    public function setPublishedOn($publishedOn)
    {
        $this->publishedOn = $publishedOn;
        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setCoverImageFile(File $image = null)
    {
        $this->coverImageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedOn = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getCoverImageFile()
    {
        return $this->coverImageFile;
    }


    /**
     * @param string $coverImage
     * @return $this;
     */
    public function setCoverImage($coverImage)
    {
        $this->coverImage = $coverImage;
        return $this;
    }


    /**
     * @return string
     */
    public function getCoverImage()
    {
        return $this->coverImage;
    }


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setEpaperFile(File $image = null)
    {
        $this->epaperFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedOn = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getEpaperFile()
    {
        return $this->epaperFile;
    }

    /**
     * @param string $epaper
     */
    public function setEpaper($epaper)
    {
        $this->epaper = $epaper;
    }

    /**
     * @return string
     */
    public function getEpaper()
    {
        return $this->epaper;
    }

}

