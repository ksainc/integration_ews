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
 * Defines the type of item to search for.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class SearchItemKindType extends Enumeration
{
    /**
     * Indicates that contacts are searched for keywords.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const CONTACT = 'Contacts';

    /**
     * Indicates that documents are searched for keywords.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const DOCUMENT = 'Docs';

    /**
     * Indicates that email messages are searched for keywords.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const EMAIL = 'Email';

    /**
     * Indicates that faxes are searched for keywords.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const FAX = 'Faxes';

    /**
     * Indicates that instant messages are searched for keywords.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const INSTANT_MESSAGE = 'Im';

    /**
     * Indicates that journals are searched for keywords.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const JOURNAL = 'Journals';

    /**
     * Indicates that meetings are searched for keywords.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const MEETING = 'Meetings';

    /**
     * Indicates that notes are searched for keywords.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const NOTE = 'Notes';

    /**
     * Indicates that posts are searched for keywords.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const POST = 'Posts';

    /**
     * Indicates that RSS feeds are searched for keywords.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const RSS_FEEDS = 'Rssfeeds';

    /**
     * Indicates that tasks are searched for keywords.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const TASK = 'Tasks';

    /**
     * Indicates that voice mails are searched for keywords.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const VOICEMAIL = 'Voicemail';
}
