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

namespace OCA\EWS\Utile;

class Validator {

    private const _fqdn = '/(?=^.{1,254}$)(^(?:(?!\d|-)[a-z0-9\-]{1,63}(?<!-)\.)+(?:[a-z]{2,})$)/i';
    private const _ip4 = '/^(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/';
    private const _ip6 = "/^(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))$/"; 
    private const _username_ad = '/^[a-zA-Z][a-zA-Z0-9\-\_\.]{0,63}\\\\[a-zA-Z][a-zA-Z0-9\-\_\.]{0,48}$/';

    /**
     * validate fully quntified domain name
     * 
     * @since Release 1.0.0
     * 
	 * @param string $fqdn - FQDN to validate
	 * 
	 * @return bool
	 */
    static function fqdn(string $fqdn): bool {

        return (!empty($fqdn) && preg_match(self::_fqdn, $fqdn) > 0);

    }

    /**
     * validate IPv4 address
     * 
     * @since Release 1.0.0
     * 
	 * @param string $ip - IPv4 address to validate
	 * 
	 * @return bool
	 */
    static function ip4(string $ip): bool {

        return (!empty($ip) && preg_match(self::_ip4, $ip) > 0);

    }

    /**
     * validate IPv6 address
     * 
     * @since Release 1.0.0
     * 
	 * @param string $ip - IPv6 address to validate
	 * 
	 * @return bool
	 */
    static function ip6(string $ip): bool {

        return (!empty($ip) && preg_match(self::_ip6, $ip) > 0);

    }

    /**
     * validate host
     * 
     * @since Release 1.0.0
     * 
	 * @param string $host - FQDN/IPv4/IPv6 address to validate
	 * 
	 * @return bool
	 */
    static function host(string $host): bool {

        if (self::fqdn($host)) {
            return true;
        }

        if (self::ip4($host)) {
            return true;
        }

        if (self::ip6($host)) {
            return true;
        }

        return false;

    }

    /**
     * validate email address
     * 
     * @since Release 1.0.0
     * 
	 * @param string $address - email address to validate
	 * 
	 * @return bool
	 */
    static function email(string $address): bool {

        return (!empty($address) && filter_var($address, FILTER_VALIDATE_EMAIL));

    }

    /**
     * validate username
     * 
     * @since Release 1.0.0
     * 
	 * @param string $username - username to validate
	 * 
	 * @return bool
	 */
    static function username(string $username): bool {

        if (self::email($username)) {
            return true;
        }

        if (self::username_ad($username)) {
            return true;
        }

        return false;

    }

    /**
     * validate windows active directory username (domain\username)
     * 
     * @since Release 1.0.15
     * 
	 * @param string $username - windows active directory formented username (domain\username)
	 * 
	 * @return bool
	 */
    static function username_ad(string $username): bool {

        return (!empty($username) && preg_match(self::_username_ad, $username) > 0);

    }

}
