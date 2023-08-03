<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\AddNewImContactToGroupResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines a response to an AddNewImContactToGroup request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class AddNewImContactToGroupResponseMessageType extends ResponseMessageType
{
    /**
     * Specifies a set of persona data.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\PersonaType
     */
    public $Persona;
}
