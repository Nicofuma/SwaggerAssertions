<?php

namespace FR3D\SwaggerAssertionsTest;

use FR3D\SwaggerAssertions\Utils;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers FR3D\SwaggerAssertions\Utils
 */
class UtilsTests extends TestCase
{
    /**
     * @dataProvider getDataForPhpize
     */
    public function testPhpize($expected, $value)
    {
        self::assertSame($expected, Utils::phpize($value));
    }

    public function getDataForPhpize()
    {
        return [
            ['', ''],
            [null, 'null'],
            [true, 'true'],
            [false, 'false'],
            [null, 'Null'],
            [true, 'True'],
            [false, 'False'],
            [0, '0'],
            [1, '1'],
            [-1, '-1'],
            [0777, '0777'],
            [255, '0xFF'],
            [100.0, '1e2'],
            [-120.0, '-1.2E2'],
            [-10100.1, '-10100.1'],
            ['-10,100.1', '-10,100.1'],
            ['1234 5678 9101 1121 3141', '1234 5678 9101 1121 3141'],
            ['1,2,3,4', '1,2,3,4'],
            ['11,22,33,44', '11,22,33,44'],
            ['11,222,333,4', '11,222,333,4'],
            ['1,222,333,444', '1,222,333,444'],
            ['11,222,333,444', '11,222,333,444'],
            ['111,222,333,444', '111,222,333,444'],
            ['1111,2222,3333,4444,5555', '1111,2222,3333,4444,5555'],
            ['foo', 'foo'],
            [6, '0b0110'],
            [[6], ['6']],
        ];
    }
}