<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\AndType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents a search expression that allows you to perform a Boolean AND
 * operation between two or more search expressions.
 *
 * The result of the AND operation is true if all the search expressions
 * contained within the And element are true.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class AndType extends MultipleOperandBooleanExpressionType
{

}
