<?php

namespace Bidhee\FrontendBundle\Extensions\Twig;

use Twig_Extension;
use Twig_SimpleFilter;
use Bidhee\FrontendBundle\Misc\NepaliCalendarHelper;

/**
 * Class NepaliDateExtension
 * @author Deepak Pandey
 */
class NepaliDateExtension extends Twig_Extension
{

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('nepaliDate', [$this, 'nepaliDateFilter']),
            new Twig_SimpleFilter('nepaliDateTime', [$this, 'nepaliDateTimeFilter']),
            new Twig_SimpleFilter('publishedOnInNepali', [$this, 'publishedOnInNepali'])
        ];
    }

    /**
     * @param $date
     * @return string
     */
    public function nepaliDateFilter($date)
    {
        $dateObj = new NepaliCalendarHelper();
        $nepaliDate = $dateObj->convertDateToNepali($date);

        return $nepaliDate['day'] . ', ' . $nepaliDate['date'] . ' ' . $nepaliDate['month_name'] . ' ' . $nepaliDate['year'];
    }

    /**
     * @param $dateTime
     * @return string
     */
    public function nepaliDateTimeFilter($dateTime)
    {
        list($nepaliDateTime, $nepaliTime) = $this->getNepaliDateTimeArray($dateTime);
        $nepaliDate = $nepaliDateTime['day'] . ', ' . $nepaliDateTime['date'] . ' ' . $nepaliDateTime['month_name'] . ' ' . $nepaliDateTime['year'];
        $time = $nepaliTime['hour'] . ' : ' . $nepaliTime['minutes'];

        return $nepaliDate . ', ' . $time;
    }


    /**
     * @param string $dateTime
     * @return string
     */
    public function publishedOnInNepali(string $dateTime)
    {
        list($nepaliDateTime, $nepaliTime) = $this->getNepaliDateTimeArray($dateTime);
        $nepaliDate = $nepaliDateTime['year'] . ' ' . $nepaliDateTime['month_name'] . ' ' . $nepaliDateTime['date'] . ' गते';
        $time = $nepaliTime['hour'] . ':' . $nepaliTime['minutes'];

        return $nepaliDate . ' ' . $time . ' मा प्रकाशित';
    }

    /**
     * @param $dateTime
     * @return array
     */
    private function getNepaliDateTimeArray($dateTime): array
    {
        $dateObj = new NepaliCalendarHelper();
        $nepaliDateTimeArray = $dateObj->convertDateToNepaliDateTime($dateTime);
        $nepaliDateTime = $nepaliDateTimeArray['nepaliDate'];
        $nepaliTime = $nepaliDateTimeArray['nepaliTime'];

        return [$nepaliDateTime, $nepaliTime];
    }

    /**
     * @return string
     */
    public function getName()
    {

        return 'nepali_date';
    }
}
