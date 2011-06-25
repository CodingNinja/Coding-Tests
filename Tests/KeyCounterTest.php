<?php

include (__DIR__ . '/../KeyCounter.php');

class KeyCounterTest extends PHPUnit_Framework_TestCase
{
    protected $list = array (
        1 => 'one',
        2 => 'two',
        3 => array (
            31 => 'thirty-one',
            32 => 'thirty-two'
        ),
        4 => 'four',
        5 => 'five',
        6 => array (
            61 => 'sixty-one',
            62 => array (
                621 => 'six hundred and twenty-one',
                622 => 'six hundred and twenty-two',
                623 => array (
                    6231 => 'six thousand two hundred and thirty-one',
                    6233 => array (
                        62331 => 'sixty-two thousand three hundred and thirty-one'
                    )
                )
            )
        ),
        7 => 'seven'
    );

    public function testCountIsCorrect() {
        $array = array (
            0 => array (
                10 => array (
                    20 => array (
                        30 => '',
                        'blah blah bad' => ''
                    )
                )
            )
        );
        $this->assertEquals ( array_count_keys ( $array ), 60 );
    }

    public function testInvalidDataIsZero() {
        $this->assertEquals(0, array_count_keys('blah blah bad input!'));
    }

    public function testCountIsCorrect2() {
        $this->assertEquals(76875, array_count_keys($this->list));
    }
}