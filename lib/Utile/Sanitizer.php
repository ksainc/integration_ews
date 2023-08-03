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

class Sanitizer {

    /**
     * sanitize string for use in folder name
     * 
     * @since Release 1.0.0
     * 
	 * @param string $name - String to be sanitized
     * @param bool $lp - Stip leading special characters
     * @param bool $tp - Stip trailing special characters
	 * 
	 * @return string sanitized version of the string
	 */
    static function folder(string $name, bool $lsc = false, bool $tsc = false): string {

        // strip forbidden characters
        $name = preg_replace("/[^\w\s\.\,\_\-\~\'\[\]\(\)]/iu",'', $name);
        // replace all white space with single space
        $name = preg_replace('/\s+/iu',' ', $name);
        // trim lenth to 255 characters
        $name = substr($name, 0, 255);
        // strip leading special characters or white space
        if ($lsc) {
            $name = preg_replace("/^[\s\.|\,|\_|\-|\~]*/iu",'', $name);
        }
        else{
            $name = ltrim($name);
        }
        // strip trailing special characters or white space
        if ($tsc) {
            $name = preg_replace("/[\s\.|\,|\_|\-|\~]*$/iu",'', $name);
        }
        else{
            $name = rtrim($name);
        }
        // return result
        return $name;

    }

    /**
     * sanitize string for use in user name
     * 
     * @since Release 1.0.0
     * 
	 * @param string $name - String to be sanitized
	 * 
	 * @return string sanitized version of the string
	 */
    static function username(string $name): string {

        // strip forbidden characters
        $name = filter_var($name, FILTER_SANITIZE_EMAIL, FILTER_FLAG_STRIP_HIGH);
        
        // return result
        return $name;

    }

}
