<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\AddNewTelUriContactToGroupResponse.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines the result data for a AddNewTelUriContactToGroup request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class AddNewTelUriContactToGroupResponse extends ResponseMessageType
{
    /**
     * Specifies a set of persona data returned by a
     * AddNewTelUriContactToGroupType request.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\PersonaType
     */
    public $Persona;
}
