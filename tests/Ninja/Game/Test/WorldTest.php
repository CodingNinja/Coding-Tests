<?php
namespace Ninja\Game\Test;

use Ninja\Game\World;

class WorldTest extends \PHPUnit_Framework_TestCase {
	
	public function testAddingTileToWorldStoresAsXAndYPosition() {
		$tile = $this->getTileMock(1,1);
		$world = new World(array($tile));
		$this->assertArrayHasKey('1:1', $world->getTiles());
	}
	
	public function testAddingMultipleTilesToOnePositionWorks() {
		$positionOneTiles = array($this->getTileMock(1,1), $this->getTileMock(1,1));
		
		$positionTwoTiles = array($this->getTileMock(1,2), $this->getTileMock(1,2));
		
		$tiles = array_merge($positionOneTiles, $positionTwoTiles);
		
		$world = new World($tiles);
		$worldTiles = $world->getTiles();
		
		$this->assertSame(2, count($worldTiles));
		$this->assertArrayHasKey('1:1', $worldTiles);
		$this->assertArrayHasKey('1:2', $worldTiles);
		
		
		$this->assertSame($positionOneTiles, $worldTiles['1:1']);
		$this->assertSame($positionTwoTiles, $worldTiles['1:2']);
	}
	
	/**
	 * 
	 */
	public function testAddingTilesBindsWorlds() {
		$world = new World();
		
		$tile = $this->getTileMock(4, 5);
		$tile->expects($this-> exactly(1))
			->method('bindWorld')
			->will($this->returnArgument(0))
		;
		
		$world->addTile($tile);
		
		$this->assertArrayHasKey('4:5', $world->getTiles());
	}
	
	
	/**
	 * @param unknown_type $x
	 * @param unknown_type $y
	 * @return \PHPUnit_Framework_MockObject_MockObject
	 */
	protected function getTileMock($x = null, $y = null) {
		$tile = $this->getMock('Ninja\\Game\\Tile\\Tile');
		
		if($x !== null) {
    		$tile->expects($this->exactly(1))
        		->method('getX')
            		->with()
            		->will($this->returnValue($x))
    		;
		}
		
		if($y !== null) {
    		$tile->expects($this->exactly(1))
        		->method('getY')
            		->with()
            		->will($this->returnValue($y))
    		;
		}
		
		return $tile;
	}

}