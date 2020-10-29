<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class IntValTest extends TestCase
{
    /**
     * @dataProvider providerForIntVal
     * @param mixed $input
     */
    public function testIntVal($input)
    {
        $this->assertTrue($this->isIntVal($input));
    }

    /**
     * @dataProvider providerForNotIntVal
     * @param mixed $input
     */
    public function testNotIntVal($input)
    {
        $this->assertFalse($this->isIntVal($input));
    }

    public function providerForIntVal()
    {
        return [
            [1],
            [-1],
            [PHP_INT_MAX],
            ['-1'],
            ['123'],
            ['0'],
            [0],
        ];
    }

    public function providerForNotIntVal()
    {
        return [
            [true],
            [false],
            [null],
            [1.1],
            ['1.0'],
        ];
    }
}
