<?php
namespace Ninja\Game\Test;

use Ninja\Game\Exception\HasWorldException;
use Ninja\Game\Exception\NoStaminaException;
use Ninja\Game\World;
use Ninja\Game\Character;
use Ninja\Game\Test\GameTest;

class CharacterTest extends GameTest {

	public function testEnterWorldUpdatesLocation() {
		$character = new Character('foo');
		$world = new World();
		$this->assertSame(array(), $world->getTiles());
		$world->addTile($character);
		$this->assertSame(array('1:1'=>array($character)), $world->getTiles());
		$this->assertSame(array(1,1), array($character->getX(), $character->getY()));
	}

	public function testStaminaMustBeAtleast1() {
		$character = new Character('foo', 100);
		$character->reduceStamniaBy(99);
		$this->assertSame(1, $character->getStamina(), 'Stamina can be 1');
	
		try {
			$character->reduceStamniaBy(1);
			$this->fail('Character was allowed to have zero stamina');
		}catch(NoStaminaException $e) {
			$this->assertSame(1, $character->getStamina(), 'Character is not allowed to have zero stamina');
		}
	}
	
	/**
	 * @dataProvider provideCharacterDirections
	 */
	public function testMoveCharacterUpdatesTilePosition(World $world, Character $character, $direction, $expectedX, $expectedY) {
		$character->move($direction);
		$key = $expectedX.':'.$expectedY;
		$worldTiles = $world->getTiles();
		$this->assertArrayHasKey($key, $worldTiles);
		$this->assertSame(array($character), $worldTiles[$key]);
		$this->assertSame(array($expectedX, $expectedY), array($character->getX(), $character->getY()));
	}
	
	/**
	 * @expectedException \InvalidArgumentException 
	 */
	public function testCharacterWontMoveInvalidDirections() {
		$character = new Character('bar');
		$world = new World();
		$world->addTile($character);
		$character->move('foo');
	}
	/**
	 * @expectedException \Ninja\Game\Exception\HasWorldException 
	 */
	public function testCharacterCannotBindMultipleWorlds() {
		$world1 = new World();
		$world2 = new World();
		$character = new Character('foobar');
		$world1->addTile($character);
		$world2->addTile($character);
	}
	
	public function provideCharacterDirections() {
		$character = new Character('foo');
		$world = new World();
		$world->addTile($character);

		return array(
			array( $world, $character, Character::DIRECTION_RIGHT, 2, 1 ),
			array( $world, $character, Character::DIRECTION_RIGHT, 3, 1 ),
			array( $world, $character, Character::DIRECTION_DOWN, 3, 2 ),
			array( $world, $character, Character::DIRECTION_DOWN, 3, 3 ),
			array( $world, $character, Character::DIRECTION_LEFT, 2, 3 ),
			array( $world, $character, Character::DIRECTION_LEFT, 1, 3 ),
			array( $world, $character, Character::DIRECTION_UP, 1, 2 ),
		);
	}
	
	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testCharacterCannotLeaveBoard() {
		$world = new World(array(), 2, 2);
		$character = new Character('foo');
		$world->addTile($character);
		$character->move('right');
		$character->move('right');
	}
}