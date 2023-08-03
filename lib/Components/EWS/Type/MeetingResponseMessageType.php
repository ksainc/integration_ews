<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\MeetingResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Type;

/**
 * Represents a meeting response in the Exchange store.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class MeetingResponseMessageType extends MeetingMessageType
{
    /**
     * Indicates the date and time of a proposed start for the meeting.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $ProposedStart;

    /**
     * Indicates the date and time of a proposed end for the meeting.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $ProposedEnd;
}
