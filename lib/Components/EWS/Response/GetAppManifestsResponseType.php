<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetAppManifestsResponseType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines the response for a GetAppManifests operation request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetAppManifestsResponseType extends ResponseMessageType
{
    /**
     * Contains information about all the XML manifest files for apps installed
     * in a mailbox.
     *
     * @since Exchange 2013 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfAppsType
     */
    public $Apps;

    /**
     * Contains a collection of base64-encoded app manifests that are installed
     * for the email account.
     *
     * @since Exchange 2013 SP1
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfAppManifestsType
     */
    public $Manifests;
}
