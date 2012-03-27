<?php
namespace Ninja\Game\Character;

use Ninja\Game\Tile\Tile;

/**
 * Contract for a Character to implement
 * 
 * A character represents a special tile which is capable of moving around the
 * world and interacting with other tiles
 *
 * @author David Mann <ninja@codingninja.com.au>
 */
interface Character extends Tile {
	
	/**
	 * @return integer The amount of stamina the character has 
	 */
	public function getStamina();
	
	/**
	 * @param integer $amount The amount to reduce the stamina by
	 * @throws \NoStaminaException Thrown when the $amount would cause the stamina of the character to go below 1
	 */
	public function reduceStamniaBy($amount = 1);
	
	/**
	 * @param string The $direction The direction to move the character
	 * @throws \InvalidArgumentException Thrown when the direction is invalid
	 * @throws \InvalidArgumentException Thrown when the the direction would cause the character to leave the world
	 */
	public function move($direction);
}