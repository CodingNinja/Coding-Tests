<?php

include (__DIR__ . '/../ListBuilder.php');

class ListBuilderTest extends PHPUnit_Framework_TestCase
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

    protected $expected = '<ul><li>1 = one</li><li>2 = two</li><li>3<ul><li>31 = thirty-one</li><li>32 = thirty-two</li></ul></li><li>4 = four</li><li>5 = five</li><li>6<ul><li>61 = sixty-one</li><li>62<ul><li>621 = six hundred and twenty-one</li><li>622 = six hundred and twenty-two</li><li>623<ul><li>6231 = six thousand two hundred and thirty-one</li><li>6233<ul><li>62331 = sixty-two thousand three hundred and thirty-one</li></ul></li></ul></li></ul></li></ul></li><li>7 = seven</li></ul>';

    public function testInvalidDataReturnsEmptyString() {
        $this->assertEquals(BuildList(''), '');
        $this->assertEquals(BuildList(0), '');
        $this->assertEquals(BuildList(false), '');
        $this->assertEquals(BuildList(new stdClass()), '');
        $this->assertEquals(BuildList(null), '');
    }

    public function testSimpleList() {
        $this->assertEquals('<ul><li>0 = Test!</li></ul>', BuildList(array('Test!')));
    }

    public function testReturnsProperList() {
        $this->assertEquals($this->expected, BuildList ( $this->list ));
    }

    public function testTraversableClassWorks() {
        $this->assertEquals($this->expected, BuildList ( new ArrayIterator($this->list) ));
    }
}