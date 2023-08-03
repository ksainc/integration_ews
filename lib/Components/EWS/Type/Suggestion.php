<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\Suggestion.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents a single meeting suggestion.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class Suggestion extends Type
{
    /**
     * Contains an array of information that describes conflicts between users
     * and resources and the suggested meeting time.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfAttendeeConflictData
     */
    public $AttendeeConflictDataArray;

    /**
     * Represents whether the suggested meeting time occurs during scheduled
     * work hours.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IsWorkTime;

    /**
     * Represents a suggested meeting time.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @todo Make a DateTime object.
     */
    public $MeetingTime;

    /**
     * Represents the quality of the suggested meeting time.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\SuggestionQuality
     */
    public $SuggestionQuality;
}
