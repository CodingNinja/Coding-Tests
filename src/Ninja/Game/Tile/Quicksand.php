<?php
namespace Ninja\Game\Tile;

use Ninja\Game\Tile\Soil;

class Quicksand extends Soil {

	public function getDensityDamage() {
		return 10;
	}
}
