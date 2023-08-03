<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\LocationSourceType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Specifies information about the origin of an associated postal address, for
 * example, a contact or a telephone book.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class LocationSourceType extends Enumeration
{
    /**
     * The information was obtained from a contact.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const CONTACT = 'Contact';

    /**
     * The information was obtained from the device.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const DEVICE = 'Device';

    /**
     * The information was obtained from location services.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const LOCATION_SERVICES = 'LocationServices';

    /**
     * There is no location source.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const NONE = 'None';

    /**
     * The information was obtained from phonebook services.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const PHONEBOOK_SERVICES = 'PhonebookServices';

    /**
     * The information was obtained from a resource.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const RESOURCE = 'Resource';
}
