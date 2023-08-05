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
 * Defines the type of folder used in a retention policy.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class ElcFolderType extends Enumeration
{
    /**
     * Indicates that the folder is an all folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const ALL = 'All';

    /**
     * Indicates that the folder is a calendar folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const CALENDAR = 'Calendar';

    /**
     * Indicates that the folder is a contacts folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const CONTACTS = 'Contacts';

    /**
     * Indicates that the folder is a conversation history folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const CONVERSATION_HISTORY = 'ConversationHistory';

    /**
     * Indicates that the folder is a deleted items folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const DELETED_ITEMS = 'DeletedItems';

    /**
     * Indicates that the folder is a drafts folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const DRAFTS = 'Drafts';

    /**
     * Indicates that the folder is an inbox folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const INBOX = 'Inbox';

    /**
     * Indicates that the folder is a journal folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const JOURNAL = 'Journal';

    /**
     * Indicates that the folder is a junk email folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const JUNK_EMAIL = 'JunkEmail';

    /**
     * Indicates that the folder is a managed custom folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const MANAGED_CUSTOM_FOLDER = 'ManagedCustomFolder';

    /**
     * Indicates that the folder is a non implemented root folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const NON_IMPLEMENTED_ROOT = 'NonIpmRoot';

    /**
     * Indicates that the folder is a notes folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const NOTES = 'Notes';

    /**
     * Indicates that the folder is a outbox folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const OUTBOX = 'Outbox';

    /**
     * Indicates that the folder is a personal folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const PERSONAL = 'Personal';

    /**
     * Indicates that the folder is a recoverable items folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const RECOVERABLE_ITEMS = 'RecoverableItems';

    /**
     * Indicates that the folder is an RSS subscription folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const RSS_SUBSCRIPTION = 'RssSubscriptions';

    /**
     * Indicates that the folder is a sent items folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const SENT_ITEMS = 'SentItems';

    /**
     * Indicates that the folder is a sync issues folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const SYNC_ISSUES = 'SyncIssues';

    /**
     * Indicates that the folder is a tasks folder.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const TASKS = 'Tasks';
}
