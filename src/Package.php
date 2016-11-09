<?php namespace CupOfTea\Package;

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
     */
    public static function getVendor()
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
     */
    public static function getPackage()
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
     */
    public static function getVersion()
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
     *
     * @return string
     */
    public static function getInfo($format = 'v/p (\vN)')
    {
        $v = self::getVendor();
        $p = self::getPackage();
        $n = self::getVersion();

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

    /**
     * Package Info.
     *
     * @return string
     * @deprecated 1.4.0
     */
    public static function package($info = false)
    {
        if ($info === false) {
            return self::getPackage();
        }

        // Retrieve package in dot notation.
        if ($info == 'dot') {
            return strtolower(str_replace('/', '.', self::PACKAGE));
        }

        // Include version
        if ($info == 'v') {
            return self::PACKAGE . ':' . self::VERSION;
        }

        return self::getPackage();
    }

    /**
     * Package Version.
     *
     * @return string
     * @deprecated 1.4.0
     */
    public static function version()
    {
        return self::getVersion();
    }
}
