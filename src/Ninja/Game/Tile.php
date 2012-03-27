<?php
namespace Ninja\Game;

use Ninja\Game\Exception\HasWorldException;

use Ninja\Game\Character\Character as CharacterInterface;

use Ninja\Game\Tile\Tile as BaseTile;

/**
 * Abstract tile implementation
 * 
 * Represents a basic tile which is capable of being placed in a world
 * 
 * @package Game
 * @subpackage Tile
 * @author David Mann <ninja@codingninja.com.au>>
 */
abstract class Tile implements BaseTile
{
	private $x;
	private $y;
	private $world;
	
    /**
     * @param integer $x The X position of the tile
     * @param integer $y The Y positon of the tile
     */
    function __construct($x, $y) {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * {@inheritdoc}
     * @see Ninja\Game\Tile.Tile::getY()
     */
    public function getY() {
        return $this->y;
    }

    /**
     * {@inheritdoc}
     * @see Ninja\Game\Tile.Tile::getX()
     */
    public function getX() {
        return $this->x;
    }
    
    /* (non-PHPdoc)
     * @see Ninja\Game\Tile.Tile::bindWorld()
     */
    public function bindWorld(World $world) {
    	if($this->world) {
    		throw new HasWorldException('The');
    	}
    	$this->world = $world;
    }
    
    /* (non-PHPdoc)
     * @see Ninja\Game\Tile.Tile::getWorld()
     */
    public function getWorld() {
    	return $this->world;
    }
}
