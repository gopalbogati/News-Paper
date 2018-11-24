<?php

namespace Bidhee\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Expose;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Bidhee\FoundationBundle\Traits\CreatedUpdateOnTrait;

/**
 * Author Bhaktaraz Bhatta
 *
 * @ORM\Table(name="ng_author")
 * @ORM\Entity(repositoryClass="Bidhee\UserBundle\Repository\AuthorRepository")
 * @Vich\Uploadable
 * @ExclusionPolicy("all")
 */
class Author
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Expose
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=200, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone1", type="string", length=255, nullable=true)
     * @Expose
     */
    private $phone1;

    /**
     * @var string
     *
     * @ORM\Column(name="phone2", type="string", length=255, nullable=true)
     */
    private $phone2;

    /**
     * @var string
     *
     * @ORM\Column(name="address1", type="string", length=255, nullable=true)
     * @Expose
     */
    private $address1;

    /**
     * @var string
     *
     * @ORM\Column(name="address2", type="string", length=255, nullable=true)
     */
    private $address2;

    /**
     * @var string
     *
     * @ORM\Column(name="bio", type="text", nullable=true)
     * @Expose
     */
    private $bio;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_profile", type="string", length=255, nullable=true)
     */
    private $facebookProfile;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_profile", type="string", length=255, nullable=true)
     */
    private $twitterProfile;

    /**
     * @var string
     *
     * @ORM\Column(name="google_plus_profile", type="string", length=255, nullable=true)
     */
    private $googlePlusProfile;

    /**
     * @var string
     *
     * @ORM\Column(name="linked_in_profile", type="string", length=255, nullable=true)
     */
    private $linkedInProfile;

    /**
     * @var string
     *
     * @ORM\Column(name="personal_domain", type="string", length=255, nullable=true)
     */
    private $personalDomain;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="author_image", fileNameProperty="imageName")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $imageName;

    use CreatedUpdateOnTrait;

    public function __toString()
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     *
     * @return Author
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Author
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone1
     *
     * @param string $phone1
     *
     * @return Author
     */
    public function setPhone1($phone1)
    {
        $this->phone1 = $phone1;

        return $this;
    }

    /**
     * Get phone1
     *
     * @return string
     */
    public function getPhone1()
    {
        return $this->phone1;
    }

    /**
     * Set phone2
     *
     * @param string $phone2
     *
     * @return Author
     */
    public function setPhone2($phone2)
    {
        $this->phone2 = $phone2;

        return $this;
    }

    /**
     * Get phone2
     *
     * @return string
     */
    public function getPhone2()
    {
        return $this->phone2;
    }

    /**
     * Set address1
     *
     * @param string $address1
     *
     * @return Author
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address1
     *
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address2
     *
     * @param string $address2
     *
     * @return Author
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set bio
     *
     * @param string $bio
     *
     * @return Author
     */
    public function setBio($bio)
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * Get bio
     *
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set facebookProfile
     *
     * @param string $facebookProfile
     *
     * @return Author
     */
    public function setFacebookProfile($facebookProfile)
    {
        $this->facebookProfile = $facebookProfile;

        return $this;
    }

    /**
     * Get facebookProfile
     *
     * @return string
     */
    public function getFacebookProfile()
    {
        return $this->facebookProfile;
    }

    /**
     * Set twitterProfile
     *
     * @param string $twitterProfile
     *
     * @return Author
     */
    public function setTwitterProfile($twitterProfile)
    {
        $this->twitterProfile = $twitterProfile;

        return $this;
    }

    /**
     * Get twitterProfile
     *
     * @return string
     */
    public function getTwitterProfile()
    {
        return $this->twitterProfile;
    }

    /**
     * Set googlePlusProfile
     *
     * @param string $googlePlusProfile
     *
     * @return Author
     */
    public function setGooglePlusProfile($googlePlusProfile)
    {
        $this->googlePlusProfile = $googlePlusProfile;

        return $this;
    }

    /**
     * Get googlePlusProfile
     *
     * @return string
     */
    public function getGooglePlusProfile()
    {
        return $this->googlePlusProfile;
    }

    /**
     * Set linkedInProfile
     *
     * @param string $linkedInProfile
     *
     * @return Author
     */
    public function setLinkedInProfile($linkedInProfile)
    {
        $this->linkedInProfile = $linkedInProfile;

        return $this;
    }

    /**
     * Get linkedInProfile
     *
     * @return string
     */
    public function getLinkedInProfile()
    {
        return $this->linkedInProfile;
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
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedOn = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param string $imageName
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }

    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPersonalDomain()
    {
        return $this->personalDomain;
    }

    /**
     * @param string $personalDomain
     */
    public function setPersonalDomain($personalDomain)
    {
        $this->personalDomain = $personalDomain;
    }

}

