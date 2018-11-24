<?php

namespace Bidhee\AdBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Bidhee\FoundationBundle\Traits\CreatedUpdateOnTrait;

/**
 * Ad
 *
 * @ORM\Table(name="ng_ad")
 * @ORM\Entity(repositoryClass="Bidhee\AdBundle\Repository\AdRepository")
 */
class Ad
{

    const PLACEMENT_HOME_BELOW_BREAKING = "home-below-breaking";
    
    const PLACEMENT_INNER_TOP = "inner-top";

    const PLACEMENT_INNER_TOP_RIGHT = "inner-top-right";

    const PLACEMENT_INNER_BEFORE_MODEL_WATCH = "inner-before-model-watch";

    const PLACEMENT_INNER_AFTER_MODEL_WATCH = "inner-after-model-watch";

    const PLACEMENT_ABOVE_HEADER_HOMEPAGE = "above-header-menu";

    const PLACEMENT_BELOW_HEADER_HOMEPAGE = "below-header-menu";

    const PLACEMENT_SIDE_SAMAJ_SECTION_HOMEPAGE = "side-samaj-menu";

    const PLACEMENT_ABOVE_SPORTS_SECTION_HOMEPAGE = "above-sports-menu";

    const PLACEMENT_BELOW_SPORTS_SECTION_HOMEPAGE = "below-sports-menu";

    const PLACEMENT_SIDE_BISHESH_SECTION_HOMEPAGE = "side-bishesh-menu";

    const PLACEMENT_BELOW_ENTERTAINMENT_SECTION_HOMEPAGE = "below-entertainment-menu";

    const PLACEMENT_ABOVE_HEALTH_SECTION_HOMEPAGE = "above-health-section";

    const PLACEMENT_RIGHT_VIDEO_SECTION_HOMEPAGE = "right-video-section";

    const PLACEMENT_RIGHT_HEALTH_SECTION_HOMEPAGE = "right-health-section";

    const PLACEMENT_HOME_AD_BELOW_MUKHAYA = "ad-below-mukhaya";


    public static function getPlacementChoices()
    {
        return [
            self::PLACEMENT_HOME_BELOW_BREAKING => "Home Page below breaking(1440x85)x5~6",
            self::PLACEMENT_HOME_AD_BELOW_MUKHAYA => "Home Page Below Mukhaya(1440x120)x5~6",
            self::PLACEMENT_INNER_TOP => "Inner Top(1140x120)x1",
            self::PLACEMENT_INNER_TOP_RIGHT => "Inner Top Right (270x105)x3~5",
            self::PLACEMENT_INNER_BEFORE_MODEL_WATCH => "Inner Before Model Watch(270x145)x1",
            self::PLACEMENT_INNER_AFTER_MODEL_WATCH => "Inner After Model Watch(270x145)x1",
            self::PLACEMENT_ABOVE_HEADER_HOMEPAGE => "Above header menu (96x780)x3",
            self::PLACEMENT_BELOW_HEADER_HOMEPAGE => "Below header menu(96x780)x3",
            self::PLACEMENT_SIDE_SAMAJ_SECTION_HOMEPAGE => "Side of samaj (155x270)x3",
            self::PLACEMENT_ABOVE_SPORTS_SECTION_HOMEPAGE => "Above of sports(102x1170)x3",
            self::PLACEMENT_BELOW_SPORTS_SECTION_HOMEPAGE => "Below of sports(115x770)x3",
            self::PLACEMENT_SIDE_BISHESH_SECTION_HOMEPAGE => "right side of bishesh(143x270)x3",
            self::PLACEMENT_BELOW_ENTERTAINMENT_SECTION_HOMEPAGE => "below entertainment (102x1170)x1",
            self::PLACEMENT_ABOVE_HEALTH_SECTION_HOMEPAGE => "Above health section  (135x870)x1",
            self:: PLACEMENT_RIGHT_VIDEO_SECTION_HOMEPAGE => "Right video section (143x270)x2",
            self:: PLACEMENT_RIGHT_HEALTH_SECTION_HOMEPAGE => "Right health section (85x270)x5"
        ];
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="placement", type="string", length=100)
     */
    private $placement;

    /**
     * @var string
     *
     * @ORM\Column(name="ad_title", type="string", length=255)
     */
    private $adTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="ad_link", type="string", length=255, nullable=true)
     */
    private $adLink;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="script", type="text", nullable=true)
     */
    private $script;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $startDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="expiry_date", type="datetime")
     */
    private $expiryDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="publish", type="boolean")
     */
    private $publish = false;

    /**
     * @var integer
     *
     * @ORM\Column(name="orderBy", type="integer")
     */
    private $orderBy;

    use CreatedUpdateOnTrait;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->adTitle;
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
     * Set orderBy
     *
     * @param integer $orderBy
     * @return Ad
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * Get orderBy
     *
     * @return integer
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @return string
     */
    public function getPlacement()
    {
        return $this->placement;
    }

    /**
     * @param string $placement
     */
    public function setPlacement($placement)
    {
        $this->placement = $placement;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Ad
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set file
     *
     * @param string $file
     *
     * @return Ad
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * @param string $script
     */
    public function setScript($script)
    {
        $this->script = $script;
    }

    /**
     * @return DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param DateTime $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * Set expiryDate
     *
     * @param DateTime $expiryDate
     *
     * @return Ad
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * Get expiryDate
     *
     * @return DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * Set publish
     *
     * @param boolean $publish
     *
     * @return Ad
     */
    public function setPublish($publish)
    {
        $this->publish = $publish;

        return $this;
    }

    /**
     * Get publish
     *
     * @return bool
     */
    public function getPublish()
    {
        return $this->publish;
    }

    /**
     * Set adLink
     *
     * @param string $adLink
     *
     * @return Ad
     */
    public function setAdLink($adLink)
    {
        $this->adLink = $adLink;

        return $this;
    }

    /**
     * Get adLink
     *
     * @return string
     */
    public function getAdLink()
    {
        return $this->adLink;
    }

    /**
     * Set adTitle
     *
     * @param string $adTitle
     *
     * @return Ad
     */
    public function setAdTitle($adTitle)
    {
        $this->adTitle = $adTitle;

        return $this;
    }

    /**
     * Get adTitle
     *
     * @return string
     */
    public function getAdTitle()
    {
        return $this->adTitle;
    }
}

