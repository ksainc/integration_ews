<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\RecipientTrackingEventType.
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
