<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\FreeBusyResponseType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents the free/busy information for a single mailbox user.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class FreeBusyResponseType extends Type
{
    /**
     * Contains availability information for a specific user.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\FreeBusyView
     */
    public $FreeBusyView;

    /**
     * Provides descriptive information about the response status.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Response\ResponseMessageType
     */
    public $ResponseMessage;
}
