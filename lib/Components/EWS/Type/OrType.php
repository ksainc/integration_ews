<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\OrType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents a search expression that performs a logical OR on the search
 * expression that it contains.
 *
 * Or will return true if any of its children return true. Or must have two or
 * more children.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class OrType extends MultipleOperandBooleanExpressionType
{

}
