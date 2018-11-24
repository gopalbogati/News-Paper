<?php

namespace Bidhee\FrontendBundle\Extensions\Twig;


use Twig_Extension;
use Twig_SimpleFilter;

class StringHelperExtension extends Twig_Extension
{

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('truncateSentence', [$this, 'truncateSentence']),
        ];
    }

    /**
     * @param $text
     * @param $limit
     * @param string $endingDelimiter
     * @return string
     */
    public function truncateSentence($text, $limit, $endingDelimiter = '...')
    {
        $output = strtok($text, " \n");
        while (--$limit > 0) {
            $output .= " " . strtok(" \n");
        }
        if ($output != $text) {
            $output .= $endingDelimiter;
        }

        return $output;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'string_helper';
    }
}
