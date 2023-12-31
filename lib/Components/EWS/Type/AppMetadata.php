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
 * Defines metadata about a mail app.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class AppMetadata extends Type
{
    /**
     * Identifies the URL that the user should navigate to, in order to fix an
     * issue indicated by the AppStatus element.
     *
     * @since Exchange 2013 SP1
     *
     * @var string
     */
    public $ActionUrl;

    /**
     * Indicates the status of the mail app.
     *
     * Possible values:
     * - Null or 0: The mail app has a healthy status.
     * - 1.0: The mail app could not be automatically updated. The mail ap
     *   needs to be re-installed from the Office Store.
     * - 1.1: The mail app could not be automatically updated. The mail app
     *   requires increased permissions, and this requires your review and
     *   confirmation to install.
     * - 1.2: The mail app couldn't be updated automatically. The current
     *   license has expired or is invalid. Please update the mail app from the
     *   Office Store.
     * - 2.0: The mail app license could not be automatically updated. The
     *   license for the mail app needs to be recovered from the Office Store.
     * - 2.1: The mail app license could not be automatically updated. The
     *   current license has expired. A new license for this app needs to be
     *   installed from the Office Store.
     * - 3.0: The Office Store status for the mail app has changed. This may
     *   indicate that there is a problem with the mail app. Go to the mail app
     *   page in the Office Store for more information.
     * - 3.1: The mail app has been removed from the Office Store.
     * - 3.2: A problem has been discovered with the mail app and it has
     *   temporarily been withdrawn from the Office Store.
     * - 3.3: The mail app will be removed from the Office Store within 30 days.
     * - 4.0: The mail app has been automatically disabled by your mail client.
     * - 4.1: The mail app has been disabled by Outlook for performance reasons.
     *
     * @since Exchange 2013 SP1
     *
     * @var string
     */
    public $AppStatus;

    /**
     * The consent state of the extension.
     *
     * @since Exchange 2016
     *
     * @var string
     */
    public $ConsentState;

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var boolean
     *
     * @todo Update once documentation exists.
     */
    public $EnabledStatus;

    /**
     * Specifies the URL for the mail app in the Office Store.
     *
     * @since Exchange 2013 SP1
     *
     * @var string
     */
    public $EndNodeUrl;

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var string
     *
     * @todo Update once documentation exists.
     */
    public $ExtensionType;

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var string
     *
     * @todo Update once documentation exists.
     */
    public $InstalledBy;

    /**
     * Whether or not the extension is mandatory.
     *
     * @since Exchange 2016
     *
     * @var boolean
     */
    public $IsMandatory;

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var string
     *
     * @todo Update once documentation exists.
     */
    public $LicenseStatus;

    /**
     * The asset id of the addin in the marketplace
     *
     * @since Exchange 2016
     *
     * @var string
     */
    public $MarketplaceAssetId;

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var string
     *
     * @todo Update once documentation exists.
     */
    public $ProductId;

    /**
     * Undocumented.
     *
     * @since Exchange 2016
     *
     * @var string
     *
     * @todo Update once documentation exists.
     * @todo Make a DateTime object.
     */
    public $TrialExpirationDate;
}
