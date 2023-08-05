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
 * Defines an instance in an array of attributes for a Persona.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PersonaAttributionType extends Type
{
    /**
     * Defines the display name of a folder, contact, distribution list,
     * delegate user, or rule.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $DisplayName;

    /**
     * Contains the identifier and change key of a folder.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\FolderIdType
     */
    public $FolderId;

    /**
     * Specifies a string that uniquely identifies an app or an attribution in a
     * persona.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Id;

    /**
     * Contains a Boolean value that indicates whether the underlying contact or
     * Active Directory recipient should be hidden or displayed as part of the
     * persona.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IsHidden;

    /**
     * Specifies a Boolean value that indicates whether the underlying contact
     * or Active Directory recipient is a quick contact.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IsQuickContact;

    /**
     * Specifies whether the underlying contact or Active Directory recipient
     * can be written to.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $IsWritable;

    /**
     * Specifies the identifier of the contact or Active Directory recipient.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $SourceId;
}
