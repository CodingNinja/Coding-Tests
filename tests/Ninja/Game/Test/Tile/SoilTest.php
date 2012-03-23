<?php
namespace Ninja\Game\Test\Tile;

use Ninja\Game\Tile\Quicksand;

use Ninja\Game\World;

use Ninja\Game\Character;

//double bound tile
// outside world tile


class SoilTest extends \PHPUnit_Framework_TestCase {

	public function testStaminaDecreasesOnEnteringSoil() {
		$character = new Character('James');
		$world = new World();
		$world->addTile(new Quicksand(1, 1));
		$world->addTile(new Quicksand(1, 2));
		$world->addTile($character);
		$this->assertSame(100, $character->getStamina());
	}
}