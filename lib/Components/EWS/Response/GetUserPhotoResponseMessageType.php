<?php
/**
 * Contains OCA\EWS\Components\EWS\Response\GetUserPhotoResponseMessageType.
 */

namespace OCA\EWS\Components\EWS\Response;

/**
 * Defines the response to a GetUserPhoto request.
 *
 * @package OCA\EWS\Components\EWS\Response
 */
class GetUserPhotoResponseMessageType extends ResponseMessageType
{
    /**
     * Indicates whether a user’s photo has changed.
     *
     * @since Exchange 2013
     *
     * @var boolean
     */
    public $HasChanged;

    /**
     * Contains the stream of picture data.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @todo Create a base64 class?
     */
    public $PictureData;
}
