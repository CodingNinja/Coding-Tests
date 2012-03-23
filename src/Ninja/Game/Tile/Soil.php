<?php
namespace Ninja\Game\Tile;

use Ninja\Game\Character\Character;

use Ninja\Game\Tile as BaseTile;

abstract class Soil extends BaseTile {
	
	public function onTileEnter(Tile $character) {
	}
	
	public function onTileLeave(Tile $tile) {
		if($tile instanceof Character) {
			$tile->reduceStaminaBy($this->getDensityDamage());
		}
	}
	
	protected abstract function getDensityDamage();
}