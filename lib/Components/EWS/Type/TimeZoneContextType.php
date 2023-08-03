<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\TimeZoneContextType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines the time zone definition that is to be used as the default when
 * assigning the time zone for the DateTime properties of objects that are
 * created, updated, and retrieved by using Exchange Web Services (EWS).
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class TimeZoneContextType extends Type
{
    /**
     * Specifies the periods and transitions that define a time zone.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\TimeZoneDefinitionType
     */
    public $TimeZoneDefinition;
}
