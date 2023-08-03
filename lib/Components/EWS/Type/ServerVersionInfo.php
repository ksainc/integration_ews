<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ServerVersionInfo.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents the Microsoft Exchange Server version number.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ServerVersionInfo extends Type
{
    /**
     * Describes the major build number.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $MajorBuildNumber;

    /**
     * Describes the major version number.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $MajorVersion;

    /**
     * Describes the minor build number.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $MinorBuildNumber;

    /**
     * Describes the minor version number.
     *
     * @since Exchange 2007
     *
     * @var integer
     */
    public $MinorVersion;

    /**
     * Describes the Exchange Web Services (EWS) schema version.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Version;
}
