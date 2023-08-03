<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\PhysicalAddressDictionaryEntryType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Describes a single physical address for a contact item.
 *
 * @package OCA\EWS\Components\EWS\Type
 *
 * @todo Create a common EntryType class.
 * @todo Create a common TextEntryType class.
 */
class PhysicalAddressDictionaryEntryType extends Type
{
    /*Constructor method with arguments*/
    public function __construct(string $Key = null,
                                string $Street = null,
                                string $City = null,
                                string $State = null,
                                string $PostalCode = null,
                                string $CountryOrRegion = null)
    {
        $this->Key = $Key;
        $this->Street = $Street;
        $this->City = $City;
        $this->State = $State;
        $this->PostalCode = $PostalCode;
        $this->CountryOrRegion = $CountryOrRegion;
    }

    /**
     * Identifies a physical address type.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\PhysicalAddressKeyType
     */
    public $Key;

    /**
     * Represents a street address for a contact item.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Street;

    /**
     * Represents the city name that is associated with a contact.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $City;

    /**
     * Represents the state of residence for a contact item.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $State;

    /**
     * Represents the postal code for a contact item.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $PostalCode;

    /**
     * Represents the country or region for a given physical address.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $CountryOrRegion;
}
