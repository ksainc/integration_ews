<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetPersonaResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines the response for a GetPersona request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetPersonaResponseMessageType extends ResponseMessageType
{
    /**
     * Specifies a set of persona data returned by a GetPersona request.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\PersonaType
     */
    public $Persona;
}
