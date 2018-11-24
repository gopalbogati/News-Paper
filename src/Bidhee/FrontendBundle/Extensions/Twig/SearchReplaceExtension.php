<?php

namespace Bidhee\FrontendBundle\Extensions\Twig;

use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Class SearchReplaceExtension
 * @author Deepak Pandey
 */
class SearchReplaceExtension extends Twig_Extension
{

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('searchReplace', [$this, 'searchReplace']),
        ];
    }

    /**
     * @param $searchText
     * @param $replaceText
     * @param $mainString
     * @return string
     */
    public function searchReplace($searchText, $replaceText, $mainString)
    {
        return (str_replace($searchText, $replaceText, $mainString));
    }

    /**
     * @return string
     */
    public function getName()
    {

        return 'str_replace';
    }
}
