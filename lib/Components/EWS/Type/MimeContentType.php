<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\MimeContentType.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents the native Multipurpose Internet Mail Extensions (MIME) stream of
 * an object that is represented in base64Binary format.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class MimeContentType extends Type
{
    /**
     * A text value that represents a base64Binary MIME stream.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $_;

    /**
     * The International Standards Organization (ISO) name of the character set
     * used in the MIME message.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $CharacterSet;
}
