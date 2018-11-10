<?php

use Waller\Gold;

class FunctionsTest extends TestCase
{
    /**
     * Test goldtoshi to btg converter.
     *
     * @param int    $goldtoshi
     * @param string $gold
     *
     * @return void
     *
     * @dataProvider goldtoshiBtgProvider
     */
    public function testToBtg($goldtoshi, $gold)
    {
        $this->assertEquals($gold, Gold\to_gold($goldtoshi));
    }

    /**
     * Test gold to goldtoshi converter.
     *
     * @param int    $goldtoshi
     * @param string $gold
     *
     * @return void
     *
     * @dataProvider goldtoshiBtgProvider
     */
    public function testToSatoshi($goldtoshi, $gold)
    {
        $this->assertEquals($goldtoshi, Gold\to_goldtoshi($gold));
    }

    /**
     * Test gold to ubtg/bits converter.
     *
     * @param int    $ubtg
     * @param string $gold
     *
     * @return void
     *
     * @dataProvider bitsBtgProvider
     */
    public function testToBits($ubtg, $gold)
    {
        $this->assertEquals($ubtg, Gold\to_ubtg($gold));
    }

    /**
     * Test gold to mbtg converter.
     *
     * @param float  $mbtg
     * @param string $gold
     *
     * @return void
     *
     * @dataProvider mBtgProvider
     */
    public function testToMbtg($mbtg, $gold)
    {
        $this->assertEquals($mbtg, Gold\to_mbtg($gold));
    }

    /**
     * Test float to fixed converter.
     *
     * @param float  $float
     * @param int    $precision
     * @param string $expected
     *
     * @return void
     *
     * @dataProvider floatProvider
     */
    public function testToFixed($float, $precision, $expected)
    {
        $this->assertSame($expected, Gold\to_fixed($float, $precision));
    }

    /**
     * Provides goldtoshi and gold values.
     *
     * @return array
     */
    public function goldtoshiBtgProvider()
    {
        return [
            [1000, '0.00001000'],
            [2500, '0.00002500'],
            [-1000, '-0.00001000'],
            [100000000, '1.00000000'],
            [150000000, '1.50000000'],
        ];
    }

    /**
     * Provides goldtoshi and ubtg/bits values.
     *
     * @return array
     */
    public function bitsBtgProvider()
    {
        return [
            [10, '0.00001000'],
            [25, '0.00002500'],
            [-10, '-0.00001000'],
            [1000000, '1.00000000'],
            [1500000, '1.50000000'],
        ];
    }

    /**
     * Provides goldtoshi and mbtg values.
     *
     * @return array
     */
    public function mBtgProvider()
    {
        return [
            [0.01, '0.00001000'],
            [0.025, '0.00002500'],
            [-0.01, '-0.00001000'],
            [1000, '1.00000000'],
            [1500, '1.50000000'],
        ];
    }

    /**
     * Provides float values with precision and result.
     *
     * @return array
     */
    public function floatProvider()
    {
        return [
            [1.2345678910, 0, '1'],
            [1.2345678910, 2, '1.23'],
            [1.2345678910, 4, '1.2345'],
            [1.2345678910, 8, '1.23456789'],
        ];
    }
}
