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

namespace OCA\EWS\Controller;

use Exception;
use Throwable;

use OCP\IConfig;
use OCP\IRequest;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;

use OCA\EWS\AppInfo\Application;

class AdminConfigurationController extends Controller {

	/**
	 * @var IConfig
	 */
	private $config;

	public function __construct($appName, IRequest $request, IConfig $config) {

		parent::__construct($appName, $request);

		$this->config = $config;
		
	}

	/**
	 * handles save configuration requests
	 *
	 * @param array $values key/value pairs to save
	 * 
	 * @return DataResponse
	 */
	public function depositConfiguration(array $values): DataResponse {
		foreach ($values as $key => $value) {
			$this->config->setAppValue(Application::APP_ID, $key, $value);
		}
		return new DataResponse(true);
	}
}