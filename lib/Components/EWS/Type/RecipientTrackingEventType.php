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
 * Represents information for a single event for a recipient.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class RecipientTrackingEventType extends Type
{
    /**
     * This element is optional.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $BccRecipient;

    /**
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $Date;

    /**
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\MessageTrackingDeliveryStatusType
     */
    public $DeliveryStatus;

    /**
     * This element is optional.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfStringsType
     */
    public $EventData;

    /**
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\MessageTrackingEventDescriptionType
     */
    public $EventDescription;

    /**
     * This element is optional.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $HiddenRecipient;

    /**
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    public $InternalId;

    /**
     * This element is optional.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfTrackingPropertiesType
     */
    public $Properties;

    /**
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     */
    public $Recipient;

    /**
     * This element is optional.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $RootAddress;

    /**
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Server;

    /**
     * This element is optional.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $UniquePathId;
}
