<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\AppType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines information about an XML manifest file for a mail app that is
 * installed in a mailbox.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class AppType extends Type
{
    /**
     * Contains the base64-encoded app manifest file.
     *
     * @since Exchange 2013 SP1
     *
     * @var string
     *
     * @todo Create a base64 class?
     */
    public $Manifest;

    /**
     * Contains metadata about the mail app.
     *
     * @since Exchange 2013 SP1
     *
     * @var \OCA\EWS\Components\EWS\Type\AppMetadata
     */
    public $Metadata;
}
