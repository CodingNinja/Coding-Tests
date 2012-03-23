<?php
namespace Ninja\Game;

use Ninja\Game\Tile\Tile;

use Ninja\Game\Exception\NoStaminaException;
use Ninja\Game\Exception\HasWorldException;
use Ninja\Game\Character\Character as BaseCharacter;
/** 
 * @author CodingNinja
 * 
 * 
 */
class Character implements BaseCharacter
{

	private $name;
	
	private $stamina;
	
	private $x = 0;
	
	private $y = 0;
	
	private $world;
	
    function __construct($name, $stamina = 100) {
        $this->name = $name;
		$this->stamina = $stamina;
    }

    /**
     * 
     * @see Character::reduceStamniaBy()
     */
    public function reduceStamniaBy($amount = 1) {
    	if($this->stamina - $amount <= 0) {
    		throw new NoStaminaException();
    	}
    	
    	$this->stamina -= $amount;
    	
    	return $this;
    }
    
    public function getWorld() {
    	return $this->world;
    }
    
    public function bindWorld(World $world) {
    	if($this->world) {
    		throw new HasWorldException();
    	}
    	
    	$this->world = $world;
    	
    	$this->enterWorld();
    }
    
    /**
     * 
     */
    protected function enterWorld() {
    	$this->onLeaveTiles(1, 1);
    	$this->x = 1;
    	$this->y = 1;
    }
    
    protected function onLeaveTiles($toX, $toY) {
    	$this->world->updatePosition($this, $toX, $toY);
    }

    public function onTileEnter(Tile $tile) {
    	 
    }
    public function onTileLeave(Tile $tile) {
    	
    }
    
    /* (non-PHPdoc)
     * @see Ninja\Game\Tile.Tile::getX()
     */
    public function getX() {
    	return $this->x;
    }

    /* (non-PHPdoc)
     * @see Ninja\Game\Tile.Tile::getY()
     */
    public function getY() {
    	return $this->y;
    }
    
    public function getStamina() {
    	return $this->stamina;
    }
    
    public function move($direction) {
    	$x = $this->x;
    	$y = $this->y;
    	if($direction === self::DIRECTION_RIGHT) {
    		$y += 1;
    	}elseif($direction === self::DIRECTION_LEFT) {
    		$y -= 1;
    	}elseif($direction === self::DIRECTION_UP) {
    		$x += 1;
    	}elseif($direction === self::DIRECTION_DOWN) {
    		$x -= 1;
    	}else{
    		throw new \InvalidArgumentException(sprintf('Unknown direction "%s"', $direction));
    	}

    	if($x < 0 || $x > $this->world->getWidth() || $y < 0 || $y > $this->world->getHeight()) {
    		throw new \InvalidArgumentException('Cannot move in direction %s, would fall off the board', $direction);
    	}
    	
    	list($tile, $x, $y) = $this->world->moveTile($this, $x, $y);
    	
    	return $this;
    }
}