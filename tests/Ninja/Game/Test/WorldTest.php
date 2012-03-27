<?php
namespace Ninja\Game\Test;

use Ninja\Game\Character;

use Ninja\Game\World;

class WorldTest extends GameTest {
	
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
		return $world;
	}
	
	public function testUpdatingPositionCleansupTiles() {
		$positionOneTiles = array($tile1 = $this->getTileMock(1,1, 2, 2), $this->getTileMock(1,1));
		
		$positionTwoTiles = array($this->getTileMock(1,2), $this->getTileMock(1,2));
		
		$tiles = array_merge($positionOneTiles, $positionTwoTiles);
		$world = new World($tiles);
		$tiles = $world->getTiles();
		$tile1
			->expects($this-> exactly(1))
			->method('getWorld')
				->with()
				->will($this->returnValue($world))
		;
		
		unset($tiles['1:1'][0]);
		$world->updatePosition($tile1, 1, 2);
		$tiles['1:2'][] = $tile1;
		$this->assertSame($tiles, $world->getTiles());
	}
	
	public function testCanWalkOverTiles() {
		$positionOneTiles = array($this->getTileMock(1,1), $this->getTileMock(1,1));
		
		$positionTwoTiles = array($this->getTileMock(1,2), $this->getTileMock(1,2));

		$character = new Character('foo');
				
		$tiles = array_merge($positionOneTiles, $positionTwoTiles, array($character));

		$world = new World($tiles);
		$worldTiles = $world->getTiles();
		$character->move('right');
		$this->assertSame(100, $character->getStamina());
		$this->assertSame(2, $character->getX());
		$this->assertSame(1, $character->getY());
		$character->move('right');
		$this->assertSame(100, $character->getStamina());
		$this->assertSame(3, $character->getX());
		$this->assertSame(1, $character->getY());
	}
	
	public function testTileMustBelongToWorldToUpdatePosition() {
		$world = new World();
		$tile = $this->getTileMock(1,1, 2, 2);
		$tile
			->expects($this->at(0))
				->method('getWorld')
					->will($this->returnValue(null));
		$tile
			->expects($this->exactly(2))
				->method('getWorld')
				->will($this->returnValue($world));
		

		try {
			$world->updatePosition($tile, 1, 2);
			$this->fail('The world updated the position of a tile which does not belong to it');
		}catch(\InvalidArgumentException $e) {
			$this->assertSame(array(), $world->getTiles());
		}

		$world->addTile($tile);
		$world->updatePosition($tile, 1, 2);
	}
	/**
	 * 
	 */
	public function testAddingTilesBindsWorlds() {
		$world = new World();
		
		$tile = $this->getTileMock(4, 5);
		$tile->expects($this-> exactly(1))
			->method('bindWorld')
				->with($world)
				->will($this->returnArgument(0))
		;
		
		$world->addTile($tile);

		$worldTiles = $world->getTiles();
		$this->assertArrayHasKey('4:5', $worldTiles, 'World was bound to the tile and the world contains the tile');
		$this->assertSame($worldTiles, array('4:5' => array($tile)), 'The world does not contain the right tile');
	}
}