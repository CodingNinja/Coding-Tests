<?php
namespace Ninja\Game;

use Ninja\Game\Tile\Tile as TileInterface;

/**
 * The World
 *
 * Represents a game "world" which is capable of having tiles placed in 
 * XY co-ordinates which a {@see Ninja\Game\Character\Character character} is capable of walking
 * around.
 *
 * @package Game
 * @author David Mann <ninja@codingninja.com.au>>
 */
class World {

	private $tiles = array();
	
	private $height;
	private $width;

	/**
	 * @param array $tiles An array of {@see TileInterface tiles}
	 * @param integer $width The width of the world
	 * @param integer $height The height pf==of the world
	 */
	public function __construct(array $tiles = array(), $width = 5, $height = 5) {
		array_map(array($this, 'addTile'), $tiles);
		$this->height = $height;
		$this->width = $width;
	}

	/**
	 * Get Tiles
	 * 
	 * This method returns all of the tiles that belong to this world.
	 * Tiles are stored at their X:Y co ordinates. E.G
	 * <code>
	 * &nbsp;array(
	 * &nbsp;    '1:1' => array(
	 * &nbsp;        Ninja\Game\Tile\Tile $tile1,
	 * &nbsp;        Ninja\Game\Tile\Tile $tile2,
	 * &nbsp;        Ninja\Game\Tile\Tile $tile3
	 * &nbsp;    )
	 * &nbsp;);
	 * </code>
	 * 
	 * @return array All of the tiles that this world contains
	 */
	public function getTiles() {
		return $this->tiles;
	}

	/**
	 * Add a tile to this world
	 * 
	 * @param TileInterface $tile The tile to add
	 * @return \Ninja\Game\World Reference to self
	 */
	public function addTile(TileInterface $tile) {
		$this->doAddTile($tile);
		$tile->bindWorld($this);

		return $this;
	}

	/**
	 * @param TileInterface $tile The tile to move
	 * @param unknown_type $toX The X position the tile is moving too
	 * @param unknown_type $toY The Y position the tile is moving too
	 * @throws \InvalidArgumentException Thrown when the tile does not belong to this world
	 * @return boolean|\Ninja\Game\World A refrence to self
	 */
	public function updatePosition(TileInterface $tile, $toX, $toY) {
		if ($tile->getWorld() !== $this) {
			throw new \InvalidArgumentException('The tile does not belong to this world');
		}

		$key = $this->generateKeyFromTile($tile);

		// Remove the tile from it's current position
		$tiles = array_filter($this->tiles[$key], function (TileInterface $v) use ($tile) {
			if ($tile === $v) {
				return false;
			}else{
				$tile->onTileLeave($v);
			}
			
			return true;
		});
		
		if(count($tiles)) {
			$this->tiles[$key] = $tiles;
		}else{
			unset($this->tiles[$key]);
		}
		
		$key = $this->generateKey(array($toX, $toY));

		$this->doAddTile($tile, $key);

		return $this;
	}

	/**
	 * Add a tile to this world
	 * 
	 * @param TileInterface $tile The tile to add
	 * @param string $key The key to store the tile at, only required if the tiles position is not yet up to date
	 * @return \Ninja\Game\World Reference to self
	 */
	protected function doAddTile(TileInterface $tile, $key = null) {
		if ($key === null) {
			$key = $this->generateKeyFromTile($tile);
		}

		if (!isset($this->tiles[$key])) {
			$this->tiles[$key] = array();
		} else {
			foreach ($this->tiles[$key] as $_tile) {
				$_tile->onTileLEave($tile);
			}
		}

		$this->tiles[$key][] = $tile;
		
		return $this;
	}

	/**
	 * @param TileInterface $tile The tile to generate a key for
	 * @return string The key
	 */
	protected function generateKeyFromTile(TileInterface $tile) {
		return $this->generateKey(array($tile->getX(), $tile->getY()));
	}

	/**
	 * @param array $params The parameters to create the key from
	 * @return string the key
	 */
	protected function generateKey(array $params) {
		return implode(':', $params);
	}
	
	/**
	 * @return integer The width of the world
	 */
	public function getWidth() {
		return $this->width;
	}
	
	/**
	 * @return integer The height of the world
	 */
	public function getHeight() {
		return $this->height;
	}
}
