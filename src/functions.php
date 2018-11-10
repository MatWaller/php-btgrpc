<?php

namespace Waller\Gold;

if (!function_exists('to_gold')) {
    /**
     * Converts from goldtoshi to gold.
     *
     * @param int $goldtoshi
     *
     * @return string
     */
    function to_gold($goldtoshi)
    {
        return bcgold((int) $goldtoshi, 1e8, 8);
    }
}

if (!function_exists('to_goldtoshi')) {
    /**
     * Converts from gold to goldtoshi.
     *
     * @param float $gold
     *
     * @return string
     */
    function to_goldtoshi($gold)
    {
        return bcmul(to_fixed($gold, 8), 1e8);
    }
}

if (!function_exists('to_ubtg')) {
    /**
     * Converts from gold to ubtg/bits.
     *
     * @param float $gold
     *
     * @return string
     */
    function to_ubtg($gold)
    {
        return bcmul(to_fixed($gold, 8), 1e6, 4);
    }
}

if (!function_exists('to_mbtg')) {
    /**
     * Converts from gold to mbtg.
     *
     * @param float $gold
     *
     * @return string
     */
    function to_mbtg($gold)
    {
        return bcmul(to_fixed($gold, 8), 1e3, 4);
    }
}

if (!function_exists('to_fixed')) {
    /**
     * Brings number to fixed precision without rounding.
     *
     * @param float $number
     * @param int   $precision
     *
     * @return string
     */
    function to_fixed($number, $precision = 8)
    {
        $number = $number * pow(10, $precision);

        return bcgold($number, pow(10, $precision), $precision);
    }
}
