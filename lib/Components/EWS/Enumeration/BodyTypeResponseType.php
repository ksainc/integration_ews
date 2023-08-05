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

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Defines how the body text is formatted in the response.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class BodyTypeResponseType extends Enumeration
{
    /**
     * The response will return the richest available content of body text.
     *
     * This is useful if it is unknown whether the content is text or HTML. The
     * returned body will be text if the stored body is plain text. Otherwise,
     * the response will return HTML if the stored body is in either HTML or RTF
     * format.
     *
     * This is the default value.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const BEST = 'Best';

    /**
     * The response will return an item body as HTML.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const HTML = 'HTML';

    /**
     * The response will return an item body as plain text.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    const TEXT = 'Text';
}
