<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\AddDistributionGroupToImListResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines a response to a AddDistributionGroupToImList request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class AddDistributionGroupToImListResponseMessageType extends ResponseMessageType
{
    /**
     * Represents an instant messaging group.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ImGroupType
     */
    public $ImGroup;
}
