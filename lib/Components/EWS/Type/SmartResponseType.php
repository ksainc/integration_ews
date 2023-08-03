<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\SmartResponseType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Base class for smart responses that include new body content.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class SmartResponseType extends SmartResponseBaseType
{
    /**
     * Represents the actual body content of a message.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\BodyType
     */
    public $NewBodyContent;
}
