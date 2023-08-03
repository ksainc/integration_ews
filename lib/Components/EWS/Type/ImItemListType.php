<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ImItemListType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines a list of instant messaging groups and instant messaging contacts.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ImItemListType extends Type
{
    /**
     * Represents an array of instant messaging (IM) groups.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfImGroupType
     */
    public $Groups;

    /**
     * Specifies an array of personas.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfPeopleType
     */
    public $Personas;
}
