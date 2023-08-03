<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\FindPeopleResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines the response to a FindPeople request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class FindPeopleResponseMessageType extends ResponseMessageType
{
    /**
     * Internal use only.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $FirstLoadedRowIndex;

    /**
     * Internal use only.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $FirstMatchingRowIndex;

    /**
     * Specifies an array of persona data.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfPeopleType
     */
    public $People;

    /**
     * Specifies the total number of personas stored on a server that are
     * returned by a FindPeople request.
     *
     * @since Exchange 2013
     *
     * @var integer
     */
    public $TotalNumberOfPeopleInView;

    /**
     * Internal use only.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $TransactionId;
}
