<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\ConvertIdResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Represents the status and result of a ConvertId operation request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class ConvertIdResponseMessageType extends ResponseMessageType
{
    /**
     * Describes a converted identifier in the response.
     *
     * @since Exchange 2007 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\AlternateIdType
     */
    public $AlternateId;
}
