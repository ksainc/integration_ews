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

namespace OCA\EWS\Components\EWS\Request;

/**
 * Base element for a request to return the manifest for apps.
 *
 * @package OCA\EWS\Components\EWS\Request
 */
class GetAppManifestsType extends BaseRequestType
{
    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var \OCA\EWS\Components\EWS\ArrayType\ArrayOfPrivateCatalogAddInsType
     *
     * @todo Update once documentation exists.
     */
    public $AddIns;

    /**
     * Contains the version of the JavaScript API for Office supported by the
     * client.
     *
     * @since Exchange 2013 SP1
     *
     * @var string
     */
    public $ApiVersionSupported;

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var string
     *
     * @todo Update once documentation exists.
     * @todo Determine if we need a ListOfExtensionIdsType implementation.
     */
    public $ExtensionIds;

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var boolean
     *
     * @todo Update once documentation exists.
     */
    public $IncludeAllInstalledAddIns;

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var boolean
     *
     * @todo Update once documentation exists.
     */
    public $IncludeCustomAppsData;

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var boolean
     *
     * @todo Update once documentation exists.
     */
    public $IncludeEntitlementData;

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var boolean
     *
     * @todo Update once documentation exists.
     */
    public $IncludeManifestData;

    /**
     * Contains the version of the manifest schema supported by the client.
     *
     * @since Exchange 2013 SP1
     *
     * @var string
     */
    public $SchemaVersionSupported;
}
