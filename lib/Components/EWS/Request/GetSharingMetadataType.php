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

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to get an opaque authentication token that identifies the
 * sharing invitation.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetSharingMetadataType extends BaseRequestType
{
    /**
     * Represents the identifier of the folder on the server that will be
     * shared.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\FolderIdType
     */
    public $IdOfFolderToShare;

    /**
     * Represents the SMTP email addresses of one or more entities that will be
     * granted access to the data in the folder that is identified by the
     * IdOfFolderToShare element.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfSmtpAddressType
     */
    public $Recipients;

    /**
     * Represents the SMTP email address that corresponds to the mailbox that
     * contains the folder that is identified by the IdOfFolderToShare element.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $SenderSmtpAddress;
}
