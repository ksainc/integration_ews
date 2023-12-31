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

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Represents a rule validation error code that describes what failed validation
 * for each rule predicate or action.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class RuleValidationErrorCodeType extends Enumeration
{
    /**
     * Indicates an Active Directory operation failure.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const AD_OPERATION_FAILURE = 'ADOperationFailure';

    /**
     * Indicates a connected account could not be found.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const CONNECTED_ACCOUNT_NOT_FOUND = 'ConnectedAccountNotFound';

    /**
     * Indicates an error creating a rule with an id.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const CREATE_WITH_RULE_ID = 'CreateWithRuleId';

    /**
     * Indicates an error duplicating an operation on the same rule.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const DUPLICATED_OPERATION_ON_THE_SAME_RULE = 'DuplicatedOperationOnTheSameRule';

    /**
     * Indicates an error with a duplicated priority.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const DUPLICATED_PRIORITY = 'DuplicatedPriority';

    /**
     * Indicates an empty value.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const EMPTY_VALUE_FOUND = 'EmptyValueFound';

    /**
     * Indicates that a folder does not exist.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const FOLDER_DOES_NOT_EXIST = 'FolderDoesNotExist';

    /**
     * Indicates an invalid address.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const INVALID_ADDRESS = 'InvalidAddress';

    /**
     * Indicates an invalid date range
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const INVALID_DATE_RANGE = 'InvalidDateRange';

    /**
     * Indicates an invalid folder id.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const INVALID_FOLDER_ID = 'InvalidFolderId';

    /**
     * Indicates an invalid size range
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const INVALID_SIZE_RANGE = 'InvalidSizeRange';

    /**
     * Indicates an invalid value.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const INVALID_VALUE = 'InvalidValue';

    /**
     * Indicates that a message classification could not be found.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const MESSAGE_CLASSIFICATION_NOT_FOUND = 'MessageClassificationNotFound';

    /**
     * Indicates an action is missing.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const MISSING_ACTION = 'MissingAction';

    /**
     * Indicates a missing parameter.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const MISSING_PARAMETER = 'MissingParameter';

    /**
     * Indicates an error MissingRangeValue.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const MISSING_RANGE_VALUE = 'MissingRangeValue';

    /**
     * Indicates a field is not settable.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const NOT_SETTABLE = 'NotSettable';

    /**
     * Indicates that a recipient does not exist.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const RECIPIENT_DOES_NOT_EXIST = 'RecipientDoesNotExist';

    /**
     * Indicates that a rule could not be found.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const RULE_NOT_FOUND = 'RuleNotFound';

    /**
     * Indicates that a size less than zero was specified.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const SIZE_LESS_THAN_ZERO = 'SizeLessThanZero';

    /**
     * Indicates that a strings value is too large.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const STRING_VALUE_TOO_BIG = 'StringValueTooBig';

    /**
     * Indicates an unknown error.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const UNEXPECTED_ERROR = 'UnexpectedError';

    /**
     * Indicates an unsupported address.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const UNSUPPORTED_ADDRESS = 'UnsupportedAddress';

    /**
     * Indicates an unsupported rule.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const UNSUPPORTED_RULE = 'UnsupportedRule';
}
