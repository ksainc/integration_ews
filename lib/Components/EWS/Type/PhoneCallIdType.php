<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\PhoneCallIdType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents the identifier of a phone call.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class PhoneCallIdType extends Type
{
    /**
     * Identifies the phone call to disconnect.
     *
     * This attribute is required.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    public $Id;
}
