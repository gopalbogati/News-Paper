<?php

/**
 * Helper functions
 */

if (!function_exists('dd')) {

    /**
     * Dump and die helper the execution.
     *
     * @author Deepak Pandey
     * @param mixed ...$params
     */
    function dd(...$params)
    {
        dump(...$params);
        die(1);
    }
}
