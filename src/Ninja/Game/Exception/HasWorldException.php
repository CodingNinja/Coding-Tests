<?php
namespace Ninja\Game\Exception;

/**
 * Represents an exception when you try and bind a tile to a world when it's already bound
 * 
 * @author davidmann
 */
class HasWorldException extends \RuntimeException
{
}