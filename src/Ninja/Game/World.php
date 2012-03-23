<?php
namespace Ninja\Game;

use Ninja\Game\Tile\Tile as TileInterface;

class World {

	private $tiles;

	public function __construct(array $tiles = array()) {
		array_map(array($this, 'addTile'), $tiles);
	}

	public function getTiles() {
		return $this->tiles;
	}

	/**
	 * @param TileInterface $tile
	 * @return \Ninja\Game\World
	 */
	public function addTile(TileInterface $tile) {
		$this->doAddTile($tile);
		$tile->bindWorld($this);

		return $this;
	}

	public function updatePosition(TileInterface $tile, $toX, $toY) {
		if ($tile->getWorld() !== $this) {
			throw new \InvalidArgumentException(
					'The tile does not belong to this world');
		}

		$key = $this->generateKeyFromTile($tile);
		$tiles = array_filter($this->tiles[$key],
				function (TileInterface $v) use ($tile) {
					if ($tile === $v) {
						return false;
					}

					return true;
				});
		$this->tiles[$key] = $tiles;
		$key = $this->generateKey(array($toX, $toY));

		$this->doAddTile($tile, $key);

		return $this;
	}

	protected function doAddTile(TileInterface $tile, $key = null) {
		if ($key === null) {
			$key = $this->generateKeyFromTile($tile);
		}else{
			throw new \Exception($key);
		}

		if (!isset($this->tiles[$key]) || !is_array($this->tiles[$key])) {
			$this->tiles[$key] = array();
		} else {
			foreach ($this->tiles[$key] as $tile) {
				$tile->onTileEnter($tile);
			}
		}

		$this->tiles[$key][] = $tile;
		
		return $this;
	}

	protected function generateKeyFromTile(TileInterface $tile) {
		return $this->generateKey(array($tile->getX(), $tile->getY()));
	}

	protected function generateKey(array $params) {
		return implode(':', $params);
	}
}
