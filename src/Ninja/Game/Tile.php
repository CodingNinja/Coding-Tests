<?php
namespace Ninja\Game;

use Ninja\Game\Character\Character as CharacterInterface;

use Ninja\Game\Tile\Tile as BaseTile;

abstract class Tile implements BaseTile
{
	private $x;
	private $y;
	private $world;
	
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
    
    public function bindWorld(World $world) {
    	$this->world = $world;
    }
    
    public function getWorld() {
    	return $this->world;
    }
}
