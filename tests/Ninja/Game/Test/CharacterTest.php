<?php
namespace Ninja\Game\Test;
use Ninja\Game\Exception\NoStaminaException;

use Ninja\Game\World;

use Ninja\Game\Character;

class CharacterTest extends \PHPUnit_Framework_TestCase {

	public function testEnterWorldUpdatesLocation() {
		$character = new Character('foo');
		
	}

	public function testStaminaMustBeAtleast1() {
		$character = new Character('foo', 100);
		$character->reduceStamniaBy(99);
		$this->assertSame(1, $character->getStamina(), 'Stamina can be 1');
	
		try {
			$character->reduceStamniaBy(1);
			$this->fail('Character was allowed to have zero stamina');
		}catch(NoStaminaException $e) {
			$this->assertSame(1, $character->getStamina(), 'Character is not allowed to have zero stamina');
		}
		
		
	}
}