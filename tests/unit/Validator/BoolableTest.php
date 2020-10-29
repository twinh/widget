<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class BoolableTest extends TestCase
{
    /**
     * @dataProvider providerForBoolVal
     * @param mixed $input
     */
    public function testBoolVal($input)
    {
        $this->assertTrue($this->isBoolable($input));
    }

    /**
     * @dataProvider providerForNotBoolVal
     * @param mixed $input
     */
    public function testNotBoolVal($input)
    {
        $this->assertFalse($this->isBoolable($input));
    }

    public function providerForBoolVal()
    {
        return [
            [true],
            [1],
            ['1'],
            [false],
            [''],
            ['0'],
            [0],
            ['on'],
            ['On'],
            ['off'],
            ['yes'],
            ['no'],
        ];
    }

    public function providerForNotBoolVal()
    {
        return [
            ['123'],
            [123],
            [1.01],
            ['y'],
            ['n'],
        ];
    }
}
