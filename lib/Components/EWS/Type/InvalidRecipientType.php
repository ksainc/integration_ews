<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\InvalidRecipientType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents the SMTP address of the invalid recipient and information about
 * why the recipient is invalid.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class InvalidRecipientType extends Type
{
    /**
     * Provides a text description of the status of the response.
     *
     * This element is optional.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $MessageText;

    /**
     * Provides an error code that identifies the specific error that the
     * request encountered.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\InvalidRecipientResponseCodeType
     */
    public $ResponseCode;

    /**
     * Contains the SMTP address of the invalid recipient.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $SmtpAddress;
}
