<?php

namespace CupOfTea\Package\Contracts;

interface Package
{
    /**
     * Get the Package vendor.
     *
     * @return string
     */
    public static function getVendor();

    /**
     * Get the Package name.
     *
     * @return string
     */
    public static function getPackage();

    /**
     * Get the Package version.
     *
     * @return string
     */
    public static function getVersion();

    /**
     * Get the Package info in a given format.
     *
     * @param string $format
     *
     * @return string
     */
    public static function getInfo($format = 'v/p (\vN)');
}
