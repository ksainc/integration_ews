<?php
/**
 * Contains OCA\EWS\Components\EWS\Request\GetUserPhotoType.
 */

namespace OCA\EWS\Components\EWS\Request;

/**
 * Defines the request to get a user’s photo.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetUserPhotoType extends BaseRequestType
{
    /**
     * Identifies the email address of the user whose photo is requested.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    public $Email;

    /**
     * Contains the requested photo size.
     *
     * @since Exchange 2013
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\UserPhotoSizeType
     */
    public $SizeRequested;

    /**
     * Specifies the type of a user photo.
     *
     * @since Exchange 2016
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\UserPhotoTypeType
     */
    public $TypeRequested;
}
