<?php
declare(strict_types=1);

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

namespace OCA\EWS\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Services\IInitialState;
use OCP\Settings\ISettings;

use OCA\EWS\AppInfo\Application;
use OCA\EWS\Service\ConfigurationService;

class AdminSettings implements ISettings {

	/**
	 * @var IInitialState
	 */
	private $initialStateService;
	/**
	 * @var ConfigurationService
	 */
	private $ConfigurationService;

	public function __construct(IInitialState $initialStateService, ConfigurationService $ConfigurationService) {
		$this->initialStateService = $initialStateService;
		$this->ConfigurationService = $ConfigurationService;
	}

	/**
	 * @return TemplateResponse
	 */
	public function getForm(): TemplateResponse {
		
		// retrieve user configuration
		$configuration = $this->ConfigurationService->retrieveSystem();
		
		$this->initialStateService->provideInitialState('admin-configuration', $configuration);

		return new TemplateResponse(Application::APP_ID, 'adminSettings');
	}

	public function getSection(): string {
		return 'integration-ews';
	}

	public function getPriority(): int {
		return 10;
	}
}
