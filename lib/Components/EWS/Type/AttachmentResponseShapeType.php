<?php
//declare(strict_types=1);

/**
* @copyright Copyright (c) 2016 James I. Armes http://jamesarmes.com/
*
* @author James I. Armes http://jamesarmes.com/
*
* @license AGPL-3.0-or-later
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as
* published by the Free Software Foundation, either version 3 of the
* License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*/

namespace OCA\EWS\Components\EWS\Type;

use OCA\EWS\Components\EWS\Type;

/**
 * Represents additional properties to return in a response to a GetAttachment
 * request.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class AttachmentResponseShapeType extends Type
{
    /**
     * Identifies additional properties to return in a response.
     *
     * This element is optional.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\NonEmptyArrayOfPathsToElementType
     */
    public $AdditionalProperties;

    /**
     * Identifies how the body text is formatted in the response.
     *
     * This element is optional.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\BodyTypeResponseType
     */
    public $BodyType;

    /**
     * Specifies whether potentially unsafe HTML content is filtered from an
     * attachment.
     *
     * This element is optional.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $FilterHtmlContent;

    /**
     * Specifies whether the Multipurpose Internet Mail Extensions (MIME)
     * content of an item or attachment is returned in the response.
     *
     * This element is optional.
     *
     * @since Exchange 2007
     *
     * @var boolean
     */
    public $IncludeMimeContent;
}
