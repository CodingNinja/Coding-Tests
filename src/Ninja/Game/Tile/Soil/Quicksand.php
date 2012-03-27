<?php
namespace Ninja\Game\Tile\Soil;

use Ninja\Game\Tile\Soil;

/**
 * Quicksand
 *
 * Quicksand is a heavy soil which uses lots of stamina to get
 * out of
 *
 * @package Game
 * @subpackage Tile
 * @author David Mann <ninja@codingninja.com.au>>
 */
class Quicksand extends Soil {
	
	/* (non-PHPdoc)
	 * @see Ninja\Game\Tile.Soil::getDensityDamage()
	 */
	protected function getDensityDamage() {
		return 10;
	}
}