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
*/

class UUID {

  public const TYPE_STANDARD_BINARY = 'SDB';
  public const TYPE_STANDARD_HEX_LONG = 'SDHL';
  public const TYPE_STANDARD_HEX_SHORT = 'SDHS';
  public const TYPE_STANDARD_HEX_BRACED = 'SDHB';
  public const TYPE_MICROSOFT_BIN_LONG = 'MSBL';
  public const TYPE_MICROSOFT_BIN_SHORT = 'MSBS';
  public const TYPE_MICROSOFT_HEX_LONG = 'MSHL';
  public const TYPE_MICROSOFT_HEX_SHORT = 'MSHS';

  private const _uuid_long = '/^[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}$/';
  private const _uuid_short = '/^[0-9a-fA-F]{32}$/i';
  private const _uuid_braced = '/^\{[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}\}$/';
  private const _uuid_ms_ol = '/^040000008200E00074C5B7101A82E008[0-9a-fA-F]{24}000000000000000010000000[0-9a-fA-F]{32}$/';
  private const _uuid_ms_vc = '/^040000008200E00074C5B7101A82E008[0-9a-fA-F]{24}0000000000000000330000007643616C2D556964010000007B[0-9a-fA-F]{72}7D00$/';

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
    if(!self::valid($namespace)) return false;

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
    if(!self::valid($namespace)) return false;

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
  public static function valid($uuid): bool {

    if (self::uuid_long($uuid)) {
      return true;
    }

    if (self::uuid_short($uuid)) {
        return true;
    }

    return false;

  }

  /**
  * validate long uuid (with dashes)
  * 
  * @since Release 1.0.32
  *  
  * @param string $uuid         uuid to validate
  * 
  * @return bool
  */
  public static function isLong(string $uuid): bool {

      return (!empty($uuid) && preg_match(self::_uuid_long, $uuid) > 0);

  }
   
  /**
   * validate short uuid (without dashes)
   * 
   * @since Release 1.0.32
   * 
   * @param string $uuid         uuid to validate
   * 
   * @return bool
   */
  public static function isShort(string $uuid): bool {

      return (!empty($uuid) && preg_match(self::_uuid_short, $uuid) > 0);

  }

  /**
   * validate braced uuid (without dashes)
   * 
   * @since Release 1.0.32
   * 
   * @param string $uuid         uuid to validate
   * 
   * @return bool
   */
  public static function isBraced(string $uuid): bool {

    return (!empty($uuid) && preg_match(self::_uuid_braced, $uuid) > 0);

  }

  /**
   * validate microsoft outlook uuid format
   * 
   * @since Release 1.0.32
   * 
   * @param string $uuid         uuid to validate
   * 
   * @return bool
   */
  public static function isMicrosoftOL(string $uuid): bool {

    $uuid = strtoupper($uuid);
    return (!empty($uuid) && preg_match(self::_uuid_ms_ol, $uuid) > 0);

  }

  /**
   * validate microsoft vcal uuid format
   * 
   * @since Release 1.0.32
   * 
   * @param string $uuid         uuid to validate
   * 
   * @return bool
   */
  public static function isMicrosoftVC(string $uuid): bool {

    $uuid = strtoupper($uuid);
    return (!empty($uuid) && preg_match(self::_uuid_ms_vc, $uuid) > 0);

  }

  /**
   * normalizes uuid to long format (13B1C4C0-D80A-467A-84B2-0811BE1E911A)
   * 
   * @since Release 1.0.32
   * 
	 * @param string $name - String to be normalize
	 * 
	 * @return string sanitized version of the string
	 */
  public static function normalize(string $value): string|null {

    // Microsoft UUID documentation
    //https://learn.microsoft.com/en-us/openspecs/exchange_server_protocols/ms-asemail/e7424ddc-dd10-431e-a0b7-5c794863370e
		//https://docs.microsoft.com/en-us/openspecs/exchange_server_protocols/ms-oxocal/1d3aac05-a7b9-45cc-a213-47f0a0a2c5c1

    $lenght = strlen($value);

    // standard uuid formats
    if ($lenght === 36 || $lenght === 32 || $lenght === 38) {
      // evaluate if value is in standard long uuid format
      if ($lenght === 36 && self::isLong($value)) {
        // normalize and return
        return strtolower($value);
      }
      // evaluate if value is in standard short uuid format
      if ($lenght === 32 && self::isShort($value)) {
        // normalize and return
        return strtolower(
          substr($value, 0, 8) . '-' . 
          substr($value, 8, 4) . '-' .
          substr($value, 12, 4) . '-' .
          substr($value, 16, 4) . '-' .
          substr($value, 20, 12)
        );
      }
      // evaluate if value is in standard braced uuid format
      if ($lenght === 38 && self::isBraced($value)) {
        // normalize and return
        return strtolower(substr($value, 1, 32));
      }
    }

    // microsoft uuid formats
    if ($lenght === 112 || $lenght === 56 || $lenght === 182 || $lenght === 91)
    {
      // evaluate if value is in binary format
      if (($lenght === 56  || $lenght === 91) && ctype_print($value) === false) {
        // convert value to hex
        $value = bin2hex($value);
        $lenght = strlen($value);
      }
      // evaluate if value is in microsoft outlook uuid format
      if ($lenght === 112 && self::isMicrosoftOL($value)) {
        // extract uuid from value
        $value = substr($value, 80, 32);
        // normalize and return
        return strtolower(
          substr($value, 0, 8) . '-' . 
          substr($value, 8, 4) . '-' .
          substr($value, 12, 4) . '-' .
          substr($value, 16, 4) . '-' .
          substr($value, 20, 12)
        );
      }
      // evaluate if value is in microsoft vCal uuid format
      if ($lenght === 182 && self::isMicrosoftVC($value)) {
        // extract uuid from value
        $value = hex2bin(substr($value, 106, 72));
        // normalize and return
        return strtolower($value);
      }
    }

		return null;
    
  }

  /**
   * converts uuid from standard format to other uuid formats
   * 
   * @since Release 1.0.32
   * 
   * @param string $value
   * 
   * @return string 
   */ 
	public static function convert(string $value, string $type = ''): string|null {
    
    // Microsoft UUID documentation
		// https://learn.microsoft.com/en-us/openspecs/exchange_server_protocols/ms-asemail/e7424ddc-dd10-431e-a0b7-5c794863370e
		// https://docs.microsoft.com/en-us/openspecs/exchange_server_protocols/ms-oxocal/1d3aac05-a7b9-45cc-a213-47f0a0a2c5c1

    // evaluate if uuid is NOT in normalized format
    if (strlen($value) !== 36 || !self::isLong($value)) {
      // normalize uuid
      $value = $this->normalize($value);
    }
    
		// Blob Id + Instance Date (YYYY-MM-DD) + Creation Stamp (YYYY-MM-DD-HH-MM-SS) + Padding
		$_ms_prefix = '040000008200E00074C5B7101A82E008' . '00000000' . '0000000000000000' . '0000000000000000';

    switch ($type) {
      case UUID::TYPE_STANDARD_HEX_SHORT:
        // remove all dashes
        return str_replace('-', '', $value);
        break;
      case UUID::TYPE_MICROSOFT_HEX_SHORT:
        // Prefix + Size + Data
        return $_ms_prefix . '10000000' . strtoupper(str_replace('-', '', $value));
        break;
      case UUID::TYPE_MICROSOFT_BIN_SHORT:
        // Prefix + Size + Data
			  return hex2bin($_ms_prefix . '10000000' . strtoupper(str_replace('-', '', $value)));
        break;
      case UUID::TYPE_MICROSOFT_HEX_LONG:
        // Prefix + Size + Data
			  return $_ms_prefix . '33000000' . bin2hex('vCal-Uid') . '01000000' . bin2hex('{' . strtoupper($value) . '}') . '00';
        break;
      case UUID::TYPE_MICROSOFT_BIN_LONG:
        // Prefix + Size + Data
			  return hex2bin($_ms_prefix . '33000000') . 'vCal-Uid' . hex2bin('01000000') . '{' . strtoupper($value) . '}' . hex2bin('00');
        break;
      default:
        return $value;
        break;
    }

		return null;

	}

}