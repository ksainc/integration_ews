<?php
//declare(strict_types=1);

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

namespace OCA\EWS\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use OCP\IUserManager;

use OCA\EWS\Service\HarmonizationService;

class Harmonize extends Command {

	public function __construct(IUserManager $userManager, HarmonizationService $HarmonizationService) {
		parent::__construct();
        $this->userManager = $userManager;
        $this->HarmonizationService = $HarmonizationService;
	}

	protected function configure() {
		$this
			->setName('ews:harmonize')
			->setDescription('Harmonizies a users contacts and calendar correlations')
            ->addArgument('user',
				InputArgument::REQUIRED,
				'User whom to harmonize');
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int {
        $uid = $input->getArgument('user');

        if (!$this->userManager->userExists($uid)) {
			$output->writeln("<error>User $uid does not exist</error>");
			return 1;
		}

		$output->writeln("<info>Starting harmonization for User $uid</info>");

        $this->HarmonizationService->performHarmonization($uid, 'M');

        $output->writeln("<info>Ended harmonization for User $uid</info>");

		return 0;

	}
}