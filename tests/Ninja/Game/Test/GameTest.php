<?php
namespace Ninja\Game\Test;

abstract class GameTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @param unknown_type $x
	 * @param unknown_type $y
	 * @return \PHPUnit_Framework_MockObject_MockObject
	 */
	protected function getTileMock($x = null, $y = null, $xCalled = 1, $yCalled = 1) {
		$tile = $this->getMock('Ninja\\Game\\Tile\\Tile');
	
		if($x !== null) {
			$tile->expects($this->exactly($xCalled))
			->method('getX')
			->with()
			->will($this->returnValue($x))
			;
		}
	
		if($y !== null) {
			$tile->expects($this->exactly($yCalled))
			->method('getY')
			->with()
			->will($this->returnValue($y))
			;
		}
	
		return $tile;
	}
}