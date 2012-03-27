<?php

namespace Ninja\Game\Test;

use Ninja\Game\Exception\HasWorldException;

use Ninja\Game\World;

use Ninja\Game\Exception\NoWorldException;

use Ninja\Game\Tile;

abstract class TileTest extends GameTest {
	
	public function testCannotHaveMultipleWorlds() {
		$world = new World();
		$world2 = new World();
		$tile = $this->createBasicTile();
		$tile->bindWorld($world);
		try {
			$tile->bindWorld($world2);
			$this->fail('A world is able to be bound to multiple worlds');
		}catch(HasWorldException $e) {
			$this->assertSame($world, $tile->getWorld(), 'A tile cannot be bound to multiple worlds');
		}
	}
	
	/**
	 * @dataProvider provideInvalidCoordinates
	 * @throwsException InvalidCoordinatesException
	 */
	public function testMustHaveValidCoordinates($x, $y) {
		$class = $this->getTileClass();
		if($class === null) {
			$this->markTestSkipped('No tile class was provided to test coordinates');
		}
		
		$tile = new $class($x, $y);
	}
	
	public function testMustBeBoundToWorldBeforeOnTileLeave() {
		$tile = $this->createBasicTile();
		$tileMock = $this->getTileMock(1,1);
		try {
			$tile->onTileLeave($tileMock);
			$this->fail('Can call onTileLeave on an unbound tile');
		}catch(NoWorldException $e) {
			$this->assertSame(true, true, 'Cannot call onTileLeave on an unbound tile');
		}
		$world = new World();
		
		$world->addTile($tile);
		$world->addTile($tileMock);
		
		$tile->onTileLeave($tileMock);
		$this->assertSame(true, true, 'Tile must be bound to a world before onTileLeave is called');
	}
	
	public function provideInvalidCoordinates() {
		return array(
			array('foo', 1),
			array(2, 'bar'),
			array(2, .4),
			array(.4, 1),
			array(-1, 1),
			array(1, -2),
			array('/', 1),
			array(array(1), 1),
			array(1, array(1)),
			array(1, new \stdClass()),
			array(new \stdClass(), 1)
		);
	}
	
	/**
	 * @return Tile 
	 */
	protected abstract function createTile();
	
	/**
	 * @return Tile 
	 */
	protected abstract function createBasicTile();
	
	/**
	 * @return string|null The FQN of the tile class| null if no tile class
	 */
	protected function getTileClass() {
		return 'Ninja\\Game\\Tile';
	}
}