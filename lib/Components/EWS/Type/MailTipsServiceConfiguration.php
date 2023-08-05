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

/**
 * Represents service configuration information for the mail tips service.
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class MailTipsServiceConfiguration extends ServiceConfiguration
{
    /**
     * Identifies the list of internal SMTP domains of the organization.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var \OCA\EWS\Components\EWS\Type\SmtpDomainList
     */
    public $InternalDomains;

    /**
     * Represents the large audience threshold for a client.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    public $LargeAudienceThreshold;

    /**
     * Indicates whether the mail tips service is available.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $MailTipsEnabled;

    /**
     * Represents the maximum message size a recipient can accept.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    public $MaxMessageSize;

    /**
     * Indicates the maximum number of recipients that can be passed to the
     * GetMailTips operation.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var integer
     */
    public $MaxRecipientsPerGetMailTipsRequest;

    /**
     * Indicates whether consumers of the GetMailTips operation have to show
     * mail tips that indicate the number of external recipients to which a
     * message is addressed.
     *
     * This element is required.
     *
     * @since Exchange 2010
     *
     * @var boolean
     */
    public $ShowExternalRecipientCount;
}
