<?php
namespace Ninja\Game;

use Ninja\Game\Tile\Tile;

use Ninja\Game\Exception\NoStaminaException;
use Ninja\Game\Exception\HasWorldException;
use Ninja\Game\Character\Character as BaseCharacter;
/** 
 * Basic Character implementation
 * 
 * Represents a character which is capable of moving around the world
 * and interacting with other tiles.
 * 
 * @author CodingNinja
 */
class Character implements BaseCharacter
{
	
	const DIRECTION_RIGHT = 'right';
	
	const DIRECTION_DOWN = 'down';
	
	const DIRECTION_LEFT = 'left';
	
	const DIRECTION_UP = 'up';

	private $name;
	private $stamina;
	private $x = 0;
	private $y = 0;
	private $world;
	
    /**
     * @param string $name The name of the character
     * @param integer $stamina The initial stamina of the character
     */
    function __construct($name, $stamina = 100) {
        $this->name = $name;
		$this->setStamina($stamina);
    }

    /* (non-PHPdoc)
     * @see Ninja\Game\Character.Character::reduceStamniaBy()
     */
    public function reduceStamniaBy($amount = 1) {
    	$this->setStamina($this->getStamina() - $amount);
    	
    	return $this;
    }
    
    /**
     * @param integer $stamina The stamina of this character
     * @throws NoStaminaException Thrown when the stamina is less than 1
     */
    protected function setStamina($stamina) {
    	if($stamina <= 0) {
    		throw new NoStaminaException();
    	}
    	
    	$this->stamina = $stamina;
    }
    
    /* (non-PHPdoc)
     * @see Ninja\Game\Tile.Tile::getWorld()
     */
    public function getWorld() {
    	return $this->world;
    }
    
    /* (non-PHPdoc)
     * @see Ninja\Game\Tile.Tile::bindWorld()
     */
    public function bindWorld(World $world) {
    	if($this->world) {
    		throw new HasWorldException();
    	}
    	
    	$this->world = $world;
    	
    	$this->enterWorld();
    }
    
    /**
     * Called when the user enters a world
     */
    protected function enterWorld() {
    	$this->onLeaveTiles(1, 1);
    	$this->x = 1;
    	$this->y = 1;
    }
    
    /**
     * @param integer $toX The X position of the tile the character is leaving
     * @param integer $toY The Y position of the tile the character is leaving
     */
    protected function onLeaveTiles($toX, $toY) {
    	$this->world->updatePosition($this, $toX, $toY);
    }

    
    /* (non-PHPdoc)
     * @see Ninja\Game\Tile.Tile::onTileLeave()
     */
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
    
    /* (non-PHPdoc)
     * @see Ninja\Game\Character.Character::getStamina()
     */
    public function getStamina() {
    	return $this->stamina;
    }
    
    
    /* (non-PHPdoc)
     * @see Ninja\Game\Character.Character::move()
     */
    public function move($direction) {
    	$x = $this->x;
    	$y = $this->y;
    	if($direction === self::DIRECTION_RIGHT) {
    		++$x;;
    	}elseif($direction === self::DIRECTION_LEFT) {
    		--$x;
    	}elseif($direction === self::DIRECTION_UP) {
    		$y--;
    	}elseif($direction === self::DIRECTION_DOWN) {
    		$y++;
    	}else{
    		throw new \InvalidArgumentException(sprintf('Unknown direction "%s"', $direction));
    	}

    	if($x < 0 || $x > $this->world->getWidth() || $y < 0 || $y > $this->world->getHeight()) {
    		throw new \InvalidArgumentException(sprintf('Cannot move in direction %s, would fall off the board', $direction));
    	}
    	
    	$this->world->updatePosition($this, $x, $y);
    	
    	$this->x = $x;
    	$this->y = $y;
    	
    	return $this;
    }
}