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
 * Defines the retention policy for a mailbox item.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class RetentionPolicyTagType extends Type
{
    /**
     * Specifies the descriptive text for the retention policy.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Description;

    /**
     * Defines the display name of the retention policy tag.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $DisplayName;

    /**
     * Indicates whether the mailbox is an archive mailbox.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IsArchive;

    /**
     * Indicates whether the retention policy is visible to users.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IsVisible;

    /**
     * Indicates whether the user opted in to the retention policy.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $OptedInto;

    /**
     * Specifies the action performed on items with the retention tag.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\RetentionActionType
     */
    public $RetentionAction;

    /**
     * Specifies the retention tag identifier.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $RetentionId;

    /**
     * Specifies the number of days that the retention policy is in effect.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $RetentionPeriod;

    /**
     * Specifies the type of folder used in a retention policy.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\ElcFolderType
     */
    public $Type;
}
