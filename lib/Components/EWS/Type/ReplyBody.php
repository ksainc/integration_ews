<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ReplyBody.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Defines the out of office (OOF) response that is sent to addresses outside
 * the recipient's domain or trusted domains.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ReplyBody extends Type
{
    /**
     * Specifies the language used in the ExternalReply message.
     *
     * The possible values for this attribute are defined by IETF RFC 3066.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $lang;

    /**
     * Contains the OOF response.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Message;
}
