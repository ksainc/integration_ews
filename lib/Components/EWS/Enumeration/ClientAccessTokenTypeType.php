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
 * Defines the type of a client access token.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ClientAccessTokenTypeType extends Enumeration
{
    /**
     * A caller identity client access token.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const CALLER_IDENTITY = 'CallerIdentity';

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var string
     *
     * @todo Update once documentation exists.
     */
    const CONNECTORS = 'Connectors';

    /**
     * An extension callback client access token.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const EXTENSION_CALLBACK = 'ExtensionCallback';

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var string
     *
     * @todo Update once documentation exists.
     */
    const EXTENSION_REST_API_CALLBACK = 'ExtensionRestApiCallback';

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var string
     *
     * @todo Update once documentation exists.
     */
    const LOKI = 'Loki';

    /**
     * Indicates that the client access token is a scoped token.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const SCOPED_TOKEN = 'ScopedToken';
}
