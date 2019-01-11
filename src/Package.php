<?php

namespace CupOfTea\Package;

use LogicException;

/**
 * Package Info.
 */
trait Package
{
    /**
     * Get the Package vendor.
     *
     * @return string
     * @throws \LogicException if self::VENDOR is not defined.
     */
    public static function getPackageVendor()
    {
        if (! defined('self::VENDOR')) {
            throw new LogicException(__CLASS__ . '::VENDOR was not defined');
        }

        return self::VENDOR;
    }

    /**
     * Get the Package name.
     *
     * @return string
     * @throws \LogicException if self::PACKAGE is not defined.
     */
    public static function getPackageName()
    {
        if (! defined('self::PACKAGE')) {
            throw new LogicException(__CLASS__ . '::PACKAGE was not defined.');
        }

        return self::PACKAGE;
    }

    /**
     * Get the Package version.
     *
     * @return string
     * @throws \LogicException if self::VERSION is not defined.
     */
    public static function getPackageVersion()
    {
        if (! defined('self::VERSION')) {
            throw new LogicException(__CLASS__ . '::VERSION was not defined.');
        }

        return self::VERSION;
    }

    /**
     * Get the Package info in a given format.
     *
     * @param string $format
     * @return string
     * @throws LogicException if any of self::VENDOR, self::PACKAGE, or self::VERSION are not defined.
     */
    public static function getPackageInfo($format = 'v/p (\vn)')
    {
        try {
            $v = self::getPackageVendor();
        } catch (LogicException $vendorException) {
        }

        try {
            $p = self::getPackageName();
        } catch (LogicException $packageException) {
            if (isset($vendorException)) {
                $packageException = new LogicException($packageException->getMessage(), $packageException->getCode(), $vendorException);
            }
        }

        try {
            $n = self::getPackageVersion();
        } catch (LogicException $versionException) {
            if (isset($packageException)) {
                throw new LogicException($versionException->getMessage(), $versionException->getCode(), $packageException);
            }

            if (isset($vendorException)) {
                throw new LogicException($versionException->getMessage(), $versionException->getCode(), $vendorException);
            }

            throw $versionException;
        }

        if (isset($packageException)) {
            throw $packageException;
        }

        if (isset($vendorException)) {
            throw $vendorException;
        }

        $toLowerCase = function ($value, $snake = false) {
            $whitespace = [
                "\xC2\xA0",
                "\xE2\x80\x80",
                "\xE2\x80\x81",
                "\xE2\x80\x82",
                "\xE2\x80\x83",
                "\xE2\x80\x84",
                "\xE2\x80\x85",
                "\xE2\x80\x86",
                "\xE2\x80\x87",
                "\xE2\x80\x88",
                "\xE2\x80\x89",
                "\xE2\x80\x8A",
                "\xE2\x80\xAF",
                "\xE2\x81\x9F",
                "\xE3\x80\x80",
            ];

            $value = str_replace($whitespace, '-', $value);
            $value = preg_replace('/[^\x20-\x7E]/u', '', $value);
            $value = preg_replace('![^-\pL\pN\s]+!u', '', $value);

            if ($snake) {
                $value = preg_replace('/(.)(?=[A-Z])/u', '$1-', $value);
            }

            $value = preg_replace('![-\s]+!u', '-', $value);
            $value = mb_strtolower($value, 'UTF-8');

            return $value;
        };

        $replacements = [
            '/^(?<!\\\)n$/i' => $n,
            '/^(?<!\\\)v$/' => $toLowerCase($v),
            '/^(?<!\\\)p$/' => $toLowerCase($p, true),
            '/^(?<!\\\)V$/' => $v,
            '/^(?<!\\\)P$/' => $p,
        ];
        $parts = preg_split('/((?<!\\\)[v,p,n])/i', $format, -1, PREG_SPLIT_DELIM_CAPTURE);

        foreach ($parts as &$part) {
            foreach ($replacements as $pattern => $replacement) {
                if (preg_match($pattern, $part)) {
                    $part = $replacement;

                    break;
                }
            }
        }

        $info = str_replace('\\', '', implode($parts));

        return $info;
    }
}
