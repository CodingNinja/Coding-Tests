<?php

include(__DIR__ . '/../Converter.php');
class ConverterTest extends PHPUnit_Framework_TestCase {

    public function testIsInstance() {
        $this->assertInstanceOf('Converter', Converter::getInstance());
    }

    /**
     * @dataProvider getConversions
     */
    public function testConvert($expected, $number) {
        $this->assertEquals($expected, Converter::getInstance()->convert($number));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBadInputThrowsException() {
        Converter::convert('blah blah bad input');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testLongNumberThrowsException() {
        Converter::convert(PHP_INT_MAX  + 1);
    }

    public function testNegativeInputReturnsNegativeString() {
        $this->assertEquals('Negative Three Hundred and Fifty Three', Converter::convert(-353));
    }

    /**
     * Data Provider for conversion function
     */
    public function getConversions() {
        return array(
            array('Zero', 0),
            array('One', 1),
            array('Seven', 7),
            array('Ten', 10),
            array('Eleven', 11),
            array('One Hundred', 100),
            array('One Hundred and Twenty Four', 124),
            array('One Thousand, Three Hundred and Sixty Eight', 1368),
            array('One Million', 1000000)
        );
    }
}