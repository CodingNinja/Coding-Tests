<?php
namespace Ninja\Game\Test\Tile;

use Ninja\Game\Tile\Soil\Quicksand;

use Ninja\Game\World;

use Ninja\Game\Character;

class QuicksandTest extends SoilTest {
	
	public function testStaminaDecreasesOnEnteringSoil() {
		$character = new Character('James');
		$world = new World();
		$soil = new Quicksand(3, 1);
		
		$world->addTile($soil);
		$world->addTile($character);
		
		$character->move('right');
		$this->assertSame(100, $character->getStamina(), 'Stamina is not reduced if not on a bad tile');
		$character->move('right');
		$this->assertSame(90, $character->getStamina(), 'Stamina was reduced by 10 when the character left the tile');
	}

	public function getTileClass() {
		return 'Ninja\\Game\\Tile\\Soil\\Quicksand';
	}
}