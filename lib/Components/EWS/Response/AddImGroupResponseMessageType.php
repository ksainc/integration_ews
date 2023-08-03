<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\AddImGroupResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines a response to an AddImGroup request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class AddImGroupResponseMessageType extends ResponseMessageType
{
    /**
     * Represents the new instant messaging group.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ImGroupType
     */
    public $ImGroup;
}
