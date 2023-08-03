<?php
/**
 * Contains OCA\EWS\Components\EWS\Enumeration\BodyTypeType.
 */

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Specifies the type of an item body.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class BodyTypeType extends Enumeration
{
    /**
     * Indicates that the body is in HTML.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const HTML = 'HTML';

    /**
     * Indicates that the body is in text.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const TEXT = 'Text';
}
