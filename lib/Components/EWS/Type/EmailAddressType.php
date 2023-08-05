<?php
//declare(strict_types=1);

/**
* @copyright Copyright (c) 2023 Sebastian Krupinski <krupinski01@gmail.com>
* @copyright Copyright (c) 2016 James I. Armes http://jamesarmes.com/
* 
* @author Sebastian Krupinski <krupinski01@gmail.com>
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
 * Identifier for a fully resolved email address
 *
 * @package OCA\EWS\Components\EWS\Type
 */
class EmailAddressType extends BaseEmailAddressType
{
    /*Constructor method with arguments*/
    public function __construct(string $Address = null, string $Name = null)
    {
        $this->EmailAddress = $Address;
        $this->Name = $Name;
    }

    /**
     * Represents the e-mail address of the mailbox user.
     *
     * @since Exchnage 2007
     *
     * @var string
     */
    public $Address;

    /**
     * The e-mail address that is represented.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $EmailAddress;

    /**
     * Specifies the item identifier for the e-mail address.
     *
     * @since Exchange 2007
     *
     * @var \OCA\EWS\Components\EWS\Type\ItemIdType
     */
    public $ItemId;

    /**
     * Specifies the type of mailbox.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\MailboxTypeType
     */
    public $MailboxType;

    /**
     * Specifies the name of the mailbox that is associated with the e-mail
     * address.
     *
     * @since Exchange 2007
     *
     * @var string
     */
    public $Name;

    /**
     * Specifies the type of routing for the e-mail address.
     *
     * @since Exchange 2007
     *
     * @var string
     *
     * @see \OCA\EWS\Components\EWS\Enumeration\RoutingType
     */
    public $RoutingType;
}
