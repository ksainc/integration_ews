<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\MembersListType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents the list of members for a distribution list.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class MembersListType extends Type
{
    /**
     * Provides an identifier for a fully resolved e-mail address, and the
     * status of that address on the server.
     *
     * This element is optional.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\MemberType[]
     */
    public $Member = array();
}
