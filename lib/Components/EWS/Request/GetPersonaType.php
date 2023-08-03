<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetPersonaType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines a request to get a persona.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetPersonaType extends BaseRequestType
{
    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType
     *
     * @todo Update once documentation exists.
     */
    public $AdditionalProperties;

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var \OCA\EWS\Components\EWS\Type\EmailAddressType
     *
     * @todo Update once documentation exists.
     */
    public $EmailAddress;

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var string
     *
     * @todo Update once documentation exists.
     */
    public $ItemLinkId;

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var \OCA\EWS\Components\EWS\Type\TargetFolderIdType
     *
     * @todo Update once documentation exists.
     */
    public $ParentFolderId;

    /**
     * Specifies the persona identifier for the associated persona.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $PersonaId;
}
