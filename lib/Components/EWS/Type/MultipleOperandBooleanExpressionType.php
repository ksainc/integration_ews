<?php
//declare(strict_types=1);

/**
* @copyright Copyright (c) 2016 James I. Armes http://jamesarmes.com/
*
* @author James I. Armes http://jamesarmes.com/
*
* @license AGPL-3.0-or-later
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as
* published by the Free Software Foundation, either version 3 of the
* License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*/

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Base class for derived elements that represent a restriction formed by two or
 * more Boolean operands.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
abstract class MultipleOperandBooleanExpressionType extends Type
{
    /**
     * Represents a search expression that enables you to perform a Boolean AND
     * operation between two or more search expressions.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\AndType
     */
    public $And;

    /**
     * Represents a search expression that determines whether a given property
     * contains the supplied constant string value.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ContainsExpressionType
     */
    public $Contains;

    /**
     * Performs a bitwise mask of the properties.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ExcludesType
     */
    public $Excludes;

    /**
     * Represents a search expression that returns true if the supplied property
     * exists on an item.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ExistsType
     */
    public $Exists;

    /**
     * Represents a search expression that compares a property with either a
     * constant value or another property and evaluates to true if they are
     * equal.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\IsEqualToType
     */
    public $IsEqualTo;

    /**
     * Represents a search expression that compares a property with either a
     * constant value or another property and returns true if the first property
     * is greater than the value or property.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\IsGreaterThanType
     */
    public $IsGreaterThan;

    /**
     * Represents a search expression that compares a property with either a
     * constant value or another property and returns true if the first property
     * is greater than or equal to the value or property.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\IsGreaterThanOrEqualToType
     */
    public $IsGreaterThanOrEqualTo;

    /**
     * Represents a search expression that compares a property with either a
     * constant value or another property and returns true if the first property
     * is less than the value or property.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\IsLessThanType
     */
    public $IsLessThan;

    /**
     * Represents a search expression that compares a property with either a
     * constant value or another property and returns true if the first property
     * is less than or equal to the value or property.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\IsLessThanOrEqualToType
     */
    public $IsLessThanOrEqualTo;

    /**
     * Represents a search expression that compares a property with either a
     * constant value or another property and returns true if the values are not
     * the same.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\IsNotEqualToType
     */
    public $IsNotEqualTo;

    /**
     * Represents a search expression that negates the Boolean value of the
     * search expression it contains.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\NotType
     */
    public $Not;

    /**
     * Represents a search expression that performs a logical OR operation on
     * the search expression it contains. The Or element will return true if any
     * of its children return true.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\OrType
     */
    public $Or;
}
