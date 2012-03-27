<?php
namespace Ninja\Game\Test\Tile;

use Ninja\Game\Test\TileTest;

use Ninja\Game\Tile\Quicksand;

use Ninja\Game\World;

use Ninja\Game\Character;

//double bound tile
// outside world tile


class SoilTest extends TileTest {
	public function testStaminaDecreasesOnEnteringSoil() {
		$character = new Character('James');
		$world = new World();
		$soilMock = $this->createTile();
		$soilMock
			->expects($this->exactly(1))
				->method('getX')
				->will($this->returnValue(2))
		;
		$soilMock
			->expects($this->exactly(1))
				->method('getY')
				->will($this->returnValue(1))
		;
		$soilMock2 = $this->createTile();
		$soilMock2
			->expects($this->exactly(1))
				->method('getX')
				->will($this->returnValue(2))
		;
		$soilMock2
			->expects($this->exactly(1))
				->method('getY')
				->will($this->returnValue(1))
		;

		$world->addTile($soilMock);
		$world->addTile($soilMock2);
		$world->addTile($character);

		$this->assertSame(100, $character->getStamina(), 'Stamina is not reduced if not on a bad tile');
		$character->move('right');
		$this->assertSame(80, $character->getStamina(), 'Stamina was reduced by 10 when the character left the tile');
	}

	public function createBasicTile() {
		$soilMock = $this->getMock('Ninja\\Game\\Tile\\Soil', array('getDensityDamage', 'getX', 'getY'), array(1, 1));
		return $soilMock;
	}
	
	public function createTile() {
		$soilMock = $this->createBasicTile();
		$soilMock
			->expects($this->exactly(1))
				->method('getDensityDamage')
				->will($this->returnValue(10))
			;
		return $soilMock;
	}
}