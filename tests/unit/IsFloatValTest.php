<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsFloatValTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForFloatVal
     * @param mixed $input
     */
    public function testFloatVal($input)
    {
        $this->assertTrue($this->isFloatVal($input));
    }

    /**
     * @dataProvider providerForNotFloatVal
     * @param mixed $input
     */
    public function testNotFloatVal($input)
    {
        $this->assertFalse($this->isFloatVal($input));
    }

    public function providerForFloatVal()
    {
        return [
            [0],
            [1],
            [99],
            [1.0],
            [1.1],
            [-1],
            ['1'],
            ['1.0'],
            ['0.1'],
            ['-1'],
            ['99'],
        ];
    }

    public function providerForNotFloatVal()
    {
        return [
            ['a'],
            [null],
            [true],
            [false],
            ['1.1.1'],
        ];
    }
}