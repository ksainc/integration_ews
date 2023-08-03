<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetNonIndexableItemDetailsResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines the response to a GetNonIndexableItemDetails request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetNonIndexableItemDetailsResponseMessageType extends ResponseMessageType
{
    /**
     * Specifies the results of the GetNonIndexableItemDetails request.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\NonIndexableItemDetailResultType
     */
    public $NonIndexableItemDetailsResult;
}
