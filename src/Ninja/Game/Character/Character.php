<?php
namespace Ninja\Game\Character;

use Ninja\Game\Tile\Tile;

interface Character extends Tile {
	public function getStamina();
	public function reduceStamniaBy($amount = 1);
}