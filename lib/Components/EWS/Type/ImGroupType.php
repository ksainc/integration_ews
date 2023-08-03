<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ImGroupType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines an instant messaging group.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ImGroupType extends Type
{
    /**
     * Contains the display name of a new instant messaging group contact or
     * the display name of a new instant messaging group.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $DisplayName;

    /**
     * Specifies the instant messaging (IM) group identifier.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ExchangeStoreId;

    /**
     * Specifies an array of additional properties
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfExtendedPropertyType
     */
    public $ExtendedProperties;

    /**
     * Specifies the group class of an instant messaging (IM) group.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $GroupType;

    /**
     * Specifies the identifiers of the contacts that are part of the instant
     * messaging (IM) group.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfItemIdsType
     */
    public $MemberCorrelationKey;

    /**
     * Represents the Simple Mail Transfer Protocol (SMTP) address of an account
     * to be used for impersonation or a Simple Mail Transfer Protocol (SMTP)
     * recipient address of a calendar or contact sharing request.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $SmtpAddress;
}
