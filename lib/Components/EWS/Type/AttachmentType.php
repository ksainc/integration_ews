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
 * Represents an Exchange attachment.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class AttachmentType extends Type
{
    /**
     * Identifies the file attachment.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\AttachmentIdType
     */
    public $AttachmentId;

    /**
     * Represents an identifier for the contents of an attachment. ContentId can
     * be set to any string value. Applications can use ContentId to implement
     * their own identification mechanisms.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $ContentId;

    /**
     * Contains the Uniform Resource Identifier (URI) that corresponds to the
     * location of the content of the attachment.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $ContentLocation;

    /**
     * Describes the Multipurpose Internet Mail Extensions (MIME) type of the
     * attachment content.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $ContentType;

    /**
     * Represents whether the attachment appears inline within an item.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $IsInline;

    /**
     * Represents when the file attachment was last modified.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $LastModifiedTime;

    /**
     * Represents the name of the attachment.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Name;

    /**
     * Represents the size in bytes of the file attachment.
     *
     * This property is read-only.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $Size;
}
