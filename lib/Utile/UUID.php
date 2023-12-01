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

/**
* UUID Class
*
* This class has static methods to generate and validate UUID's.
* This class is based on a comment by Andrew Moore on php.net
* @see http://www.php.net/manual/en/function.uniqid.php#94959
*
*/

class UUID {

    /**
     * Generates version 3 UUID
     * 
     * Version 3 are named based. They require a namespace (another valid UUID) and a value (the name). 
     * 
     * @since Release 1.0.0
     * 
     * @param string $namespace   another valid UUID
     * @param string $name        random value
     * 
     * @return string a version 3 UUID
     */ 
    public static function v3($namespace, $name) {
      if(!self::is_valid($namespace)) return false;

      // Get hexadecimal components of namespace
      $nhex = str_replace(array('-','{','}'), '', $namespace);

      // Binary Value
      $nstr = '';

      // Convert Namespace UUID to bits
      for($i = 0; $i < strlen($nhex); $i+=2) {
        $nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
      }

      // Calculate hash value
      $hash = md5($nstr . $name);

      return sprintf('%08s-%04s-%04x-%04x-%12s',

        // 32 bits for "time_low"
        substr($hash, 0, 8),

        // 16 bits for "time_mid"
        substr($hash, 8, 4),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 3
        (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

        // 48 bits for "node"
        substr($hash, 20, 12)
      );
    }

    /**
     * Generates version 4 UUID
     * 
     * @since Release 1.0.0
     * 
     * @return string a version 4 UUID
     */
    public static function v4() {
      return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
      );
    }

    /**
     * Generates version 5 UUID
     * 
     * Version 5 are named based. They require a namespace (another valid UUID) and a value (the name). 
     * 
     * @since Release 1.0.0
     * 
     * @param string $namespace   another valid UUID
     * @param string $name        random value
     * 
     * @return string a version 5 UUID
     */ 
    public static function v5($namespace, $name) {
      if(!self::is_valid($namespace)) return false;

      // Get hexadecimal components of namespace
      $nhex = str_replace(array('-','{','}'), '', $namespace);

      // Binary Value
      $nstr = '';

      // Convert Namespace UUID to bits
      for($i = 0; $i < strlen($nhex); $i+=2) {
        $nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
      }

      // Calculate hash value
      $hash = sha1($nstr . $name);

      return sprintf('%08s-%04s-%04x-%04x-%12s',

        // 32 bits for "time_low"
        substr($hash, 0, 8),

        // 16 bits for "time_mid"
        substr($hash, 8, 4),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 5
        (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

        // 48 bits for "node"
        substr($hash, 20, 12)
      );
    }

    /**
     * Validates UUID
     * 
     * @since Release 1.0.0
     *
     * @param string $uuid a valid or invalied uuid
     * 
     * @return bool
     */ 
    public static function is_valid($uuid) {

      if (preg_match('/^[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}$/i', $uuid) > 0){
        return true;
      }
      elseif (preg_match('/[0-9a-fA-F]{32}$/i', $uuid) > 0) {
        return true;
      }

      return false;

    }

}