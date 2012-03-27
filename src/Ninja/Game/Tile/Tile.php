<?php
namespace Ninja\Game\Tile;

use Ninja\Game\Exception\HasWorldException;
use Ninja\Game\Exception\NoWorldException;
use Ninja\Game\World;

/**
 * Contract for a Tile to implement
 *
 * A tile is an object which is capable of being placed on a map
 *
 * @author CodingNinja
 */
interface Tile {
	
	/**
	 * @return integer The X Position of this tile 
	 */
	public function getX();
	
	/**
	 * @return integer The Y position of this tile
	 */
	public function getY();
	
	/**
	 * Bind this tile to a world
	 * 
	 * @param World $world
	 * @throws HasWorldException Thrown when the tile is already bound to a world
	 */
	public function bindWorld(World $world);
	
	/**
	 * @return World The world the tile has been bound too
	 * @throws NoWorldException Thrown when the tile has no world
	 */
	public function getWorld();

	/**
     * @param Tile The tile that is leaving this tile
     */
    public function onTileLeave(Tile $tile);
}