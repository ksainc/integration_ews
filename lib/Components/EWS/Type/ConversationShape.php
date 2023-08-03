<?php
/**
 * Contains OCA\EWS\Components\EWS\Type\ConversationShape.
 */

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Identifies the property set to return in a FindConversation operation
 * response.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class ConversationShape extends Type
{
    /**
     * Identifies additional properties for use in the request.
     *
     * @since Exchange 2013
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType
     */
    public $AdditionalProperties;

    /**
     * Identifies the set of properties to return in the response.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\DefaultShapeNamesType
     */
    public $BaseShape;
}
