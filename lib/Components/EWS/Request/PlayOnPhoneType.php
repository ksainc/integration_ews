<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\PlayOnPhoneType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Represents a request to read an item on a phone.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class PlayOnPhoneType extends BaseRequestType
{
    /**
     * Represents the dial string of the phone number that is called to play an
     * item by phone.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $DialString;

    /**
     * Represents the identifier of an item to play on a phone.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ItemId;
}
