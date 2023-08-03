<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\ResolveNamesResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines the status and result of a ResolveNames operation request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class ResolveNamesResponseMessageType extends ResponseMessageType
{
    /**
     * Contains an array of resolutions for an ambiguous name.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfResolutionType
     */
    public $ResolutionSet;
}
