<?php
/**
 * Contains OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayofLegacyDNsType.
 */

namespace OCA\EWS\Components\EWS\ArrayType;

use OCA\EWS\Components\EWS\ArrayType;

/**
 * Defines an array of mailboxes identified by legacy distinguished name.
 *
 * @package OCA\EWS\Components\EWS\Array
 */
class NonEmptyArrayofLegacyDNsType extends ArrayType
{
    /**
     * Identifies a mailbox by its legacy distinguished name.
     *
     * @since Exchange 2013
     *
     * @var string[]
     */
    public $LegacyDN = array();
}
