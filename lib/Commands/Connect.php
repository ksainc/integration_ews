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

use OCA\EWS\Service\CoreService;

class Connect extends Command {

	public function __construct(IUserManager $userManager, CoreService $CoreService) {
		parent::__construct();
        $this->userManager = $userManager;
        $this->CoreService = $CoreService;
	}

	protected function configure() {
		$this
			->setName('ews:connect')
			->setDescription('Connects a user to EWS Server')
            ->addArgument('user',
				InputArgument::REQUIRED,
				'User whom to connect to the EWS Server')
			->addArgument('provider',
				InputArgument::REQUIRED,
				'FQDN or IP address of the EWS Server')
            ->addArgument('accountid',
                InputArgument::REQUIRED,
                'The username of the account to connect to on the EWS Server')
            ->addArgument('accountsecret',
                InputArgument::REQUIRED,
                'The password of the account to connect to on the EWS Server')
            ->addArgument('validate',
                InputArgument::OPTIONAL,
                'Should we validate the credentials with EWS Server. (Default false)');
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int {
        $uid = $input->getArgument('user');
        $account_provider = $input->getArgument('provider');
        $account_id = $input->getArgument('accountid');
        $account_secret = $input->getArgument('accountsecret');
        $validate = $input->getArgument('validate');

        if (!$this->userManager->userExists($uid)) {
			$output->writeln("<error>User $uid does not exist</error>");
			return 1;
		}

        $this->CoreService->connectAccount($uid, $account_provider, $account_id, $account_secret, $validate);

        $output->writeln("<info>User $uid connected to $account_provider as $account_id</info>");

		return 0;

	}
}