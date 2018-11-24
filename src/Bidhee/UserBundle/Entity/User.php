<?php

namespace Bidhee\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Expose;
use Gedmo\Mapping\Annotation as Gedmo;
use \FOS\UserBundle\Model\User as BaseUser;
use Bidhee\FoundationBundle\Traits\CreatedUpdateOnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Table(name="ng_users")
 * @ORM\Entity(repositoryClass="Bidhee\UserBundle\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="Email already used")
 * @UniqueEntity(fields={"username"}, message="Username already used")
 *
 */
class User extends BaseUser
{

    const ROLE_AUTHOR = 'ROLE_AUTHOR';

    const ROLE_EDITOR = 'ROLE_EDITOR';

    const ROLE_SYSTEM_ADMIN = 'ROLE_SYSTEM_ADMIN';

    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    public static $definedRoles = [
        self::ROLE_AUTHOR => 'Author',
        self::ROLE_EDITOR => 'Editor',
        self::ROLE_SYSTEM_ADMIN => 'Admin',
        self::ROLE_SUPER_ADMIN => 'Superadmin'
    ];

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Expose
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @Expose
     * @ORM\Column(type="string", length=255, nullable=true )
     */
    protected $phone1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true )
     * @Expose
     */
    protected $phone2;

    /**
     * @Expose
     * @ORM\Column(type="string", length=255, nullable=true )
     */
    protected $address1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true )
     * @Expose
     */
    protected $address2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true )
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true )
     */
    protected $state;

    /**
     * @ORM\Column(type="string", length=25, nullable=true )
     */
    protected $zip;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $country;

    /**
     * @ORM\Column(type="string", length=255 )
     */
    protected $description = 'description';


    use CreatedUpdateOnTrait;

    protected $userGroup;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $gcmId;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Expose
     */
    private $token;

    /**
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    protected $facebookId;

    /**
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    protected $facebookAccessToken;

    /**
     * @ORM\Column(name="twitter_access_token", type="string", length=255, nullable=true)
     */
    protected $twitterAccessToken;

    /**
     * @ORM\Column(name="twitter_access_token_secret", type="string", length=255, nullable=true)
     */
    protected $twitterAccessTokenSecret;

    /**
     * @ORM\Column(name="profile_image_url", type="string", length=255, nullable=true)
     */
    protected $profileImageUrl;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getUserGroup()
    {
        return $this->userGroup;
    }

    /**
     * @param mixed $userGroup
     *
     * @return User
     */
    public function setUserGroup($userGroup)
    {
        $this->userGroup = $userGroup;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone1()
    {
        return $this->phone1;
    }

    /**
     * @param string $phone1
     *
     * @return User
     */
    public function setPhone1($phone1)
    {
        $this->phone1 = $phone1;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone2()
    {
        return $this->phone2;
    }

    /**
     * @param string $phone2
     *
     * @return User
     */
    public function setPhone2($phone2)
    {
        $this->phone2 = $phone2;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * @param string $address1
     *
     * @return User
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param string $address2
     *
     * @return User
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     *
     * @return User
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param string $zip
     *
     * @return User
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return User
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param mixed $facebookId
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
    }

    /**
     * @return mixed
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * @param mixed $facebookAccessToken
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getGcmId()
    {
        return $this->gcmId;
    }

    /**
     * @param mixed $gcmId
     */
    public function setGcmId($gcmId)
    {
        $this->gcmId = $gcmId;
    }

    /**
     * @return mixed
     */
    public function getTwitterAccessToken()
    {
        return $this->twitterAccessToken;
    }

    /**
     * @param mixed $twitterAccessToken
     */
    public function setTwitterAccessToken($twitterAccessToken)
    {
        $this->twitterAccessToken = $twitterAccessToken;
    }

    /**
     * @return mixed
     */
    public function getTwitterAccessTokenSecret()
    {
        return $this->twitterAccessTokenSecret;
    }

    /**
     * @param mixed $twitterAccessTokenSecret
     */
    public function setTwitterAccessTokenSecret($twitterAccessTokenSecret)
    {
        $this->twitterAccessTokenSecret = $twitterAccessTokenSecret;
    }

    /**
     * @return mixed
     */
    public function getProfileImageUrl()
    {
        return $this->profileImageUrl;
    }

    /**
     * @param mixed $profileImageUrl
     */
    public function setProfileImageUrl($profileImageUrl)
    {
        $this->profileImageUrl = $profileImageUrl;
    }

}

