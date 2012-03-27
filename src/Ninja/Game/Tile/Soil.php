<?php
namespace Ninja\Game\Tile;

use Ninja\Game\Exception\NoWorldException;

use Ninja\Game\Character\Character;

use Ninja\Game\Tile as BaseTile;

/**
 * Abstract Soil implementation
 *
 * Soil is a basic tile which reduces a users stamina once they leave
 *
 * @package Game
 * @subpackage Tile
 * @author David Mann <ninja@codingninja.com.au>>
 */
abstract class Soil extends BaseTile {
	
	/* (non-PHPdoc)
	 * @see Ninja\Game\Tile.Tile::onTileLeave()
	 */
	public function onTileLeave(Tile $tile) {
		if(!$this->getWorld()) {
			throw new NoWorldException('Cannot act on tile when not bound to a world');
		}
		
		if($tile instanceof Character) {
			$tile->reduceStamniaBy($this->getDensityDamage());
		}
	}
	
	/**
	 * @return integer The amount of stamina it costs to move off the tile 
	 */
	protected abstract function getDensityDamage();
}