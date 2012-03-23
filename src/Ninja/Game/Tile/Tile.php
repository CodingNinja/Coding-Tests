<?php
namespace Ninja\Game\Tile;

/**
 * Contract for a Tile to implement
 * 
 * A tile is an object which is capable of being placed on a map
 * 
 * @author CodingNinja
 */
use Ninja\Game\Exception\NoStaminaException;

use Ninja\Game\World;

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
	 * @return World The world the tile has been bound too
	 * @throws NoStaminaException Thrown when the tile has no world
	 */
	public function getWorld();
	
	public function bindWorld(World $world);
    
    /**
     * 
     */
    public function onTileEnter(Tile $tile);
    
    /**
     * 
     */
    public function onTileLeave(Tile $tile);
}