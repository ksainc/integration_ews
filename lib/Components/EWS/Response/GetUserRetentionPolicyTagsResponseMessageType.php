<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetUserRetentionPolicyTagsResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines the response to a GetRetentionPolicyTags request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetUserRetentionPolicyTagsResponseMessageType extends ResponseMessageType
{
    /**
     * Contains a list of retention tags.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfRetentionPolicyTagsType
     */
    public $RetentionPolicyTags;
}
