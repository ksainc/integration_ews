<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\SharingDataType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Describes the type of data that is shared by a shared folder.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class SharingDataType extends Enumeration
{
    /**
     * Indicates that the shared folder contains calendar information.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const CALENDAR = 'Calendar';

    /**
     * Indicates that the shared folder contains contact information.
     *
     * @since Exchange 2010
     *
     * @var string
     */
    const CONTACTS = 'Contacts';
}
