<?php
/**
 * @copyright Copyright (c) 2023 Sebastian Krupinski <krupinski01@gmail.com>
 *
 * @author Sebastian Krupinski <krupinski01@gmail.com>
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

declare(strict_types=1);

return [
	'routes' => [
		['name' => 'AdminConfiguration#depositConfiguration', 'url' => '/admin-configuration', 'verb' => 'PUT'],
		['name' => 'UserConfiguration#ConnectAlternate', 'url' => '/connect-alternate', 'verb' => 'GET'],
		['name' => 'UserConfiguration#ConnectO365', 'url' => '/connect-o365', 'verb' => 'GET'],
		['name' => 'UserConfiguration#Disconnect', 'url' => '/disconnect', 'verb' => 'GET'],
		['name' => 'UserConfiguration#Harmonize', 'url' => '/harmonize', 'verb' => 'GET'],
		['name' => 'UserConfiguration#Test', 'url' => '/test', 'verb' => 'GET'],
		['name' => 'UserConfiguration#fetchLocalCollections', 'url' => '/fetch-local-collections', 'verb' => 'GET'],
		['name' => 'UserConfiguration#fetchRemoteCollections', 'url' => '/fetch-remote-collections', 'verb' => 'GET'],
		['name' => 'UserConfiguration#fetchCorrelations', 'url' => '/fetch-correlations', 'verb' => 'GET'],
		['name' => 'UserConfiguration#depositCorrelations', 'url' => '/deposit-correlations', 'verb' => 'PUT'],
		['name' => 'UserConfiguration#fetchPreferences', 'url' => '/fetch-preferences', 'verb' => 'GET'],
		['name' => 'UserConfiguration#depositPreferences', 'url' => '/deposit-preferences', 'verb' => 'PUT'],
	]
];
