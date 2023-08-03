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

use OCP\IConfig;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Services\IInitialState;
use OCP\Settings\ISettings;

use OCA\EWS\AppInfo\Application;
use OCA\EWS\Service\HarmonizationService;

class AdminSettings implements ISettings {

	/**
	 * @var IConfig
	 */
	private $config;
	/**
	 * @var IInitialState
	 */
	private $initialStateService;

	public function __construct(IConfig $config, IInitialState $initialStateService, HarmonizationService $HarmonizationService) {
		$this->config = $config;
		$this->initialStateService = $initialStateService;
		$this->HarmonizationService = $HarmonizationService;
	}

	/**
	 * @return TemplateResponse
	 */
	public function getForm(): TemplateResponse {
		
		$configuration = [
			'harmonization_mode' => $this->HarmonizationService->getMode(),
			'harmonization_thread_duration' => $this->HarmonizationService->getThreadDuration(),
			'harmonization_thread_pause' => $this->HarmonizationService->getThreadPause()
		];

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
