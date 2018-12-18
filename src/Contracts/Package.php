<?php

namespace CupOfTea\Package\Contracts;

interface Package
{
    /**
     * Get the Package vendor.
     *
     * @return string
     */
    public static function getPackageVendor();

    /**
     * Get the Package name.
     *
     * @return string
     */
    public static function getPackageName();

    /**
     * Get the Package version.
     *
     * @return string
     */
    public static function getPackageVersion();

    /**
     * Get the Package info in a given format.
     *
     * @param string $format
     * @return string
     */
    public static function getPackageInfo($format = 'v/p (\vn)');
}
