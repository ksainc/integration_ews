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

namespace OCA\EWS\Components\EWS;

use BadMethodCallException;
use CurlHandle;
use RuntimeException;

/**
 * Exchange Web Services Client
 *
 * @package OCA\EWS\Components\EWS\EWSClient
 */
class EWSClient extends \SoapClient
{

    /**
     * URI Scheme
     *
     * @var string
     */
    const URI_SCHEME_HTTP = 'http://';
    /**
     * URI Scheme
     *
     * @var string
     */
    const URI_SCHEME_HTTPS = 'https://';
    /**
     * URI Path
     *
     * @var string
     */
    const URI_PATH = '/EWS/Exchange.asmx';
    /**
     * Exchange Web Services Version 2007
     *
     * @var string
     */
    const VERSION_2007 = 'Exchange2007';
    /**
     * Exchange Web Services Version 2007 SP1
     *
     * @var string
     */
    const VERSION_2007_SP1 = 'Exchange2007_SP1';
    /**
     * Exchange Web Services Version 2007 SP2
     *
     * @var string
     */
    const VERSION_2009 = 'Exchange2009';
    /**
     * Exchange Web Services Version 2010
     *
     * @var string
     */
    const VERSION_2010 = 'Exchange2010';
    /**
     * Exchange Web Services Version 2010 SP1
     *
     * @var string
     */
    const VERSION_2010_SP1 = 'Exchange2010_SP1';
    /**
     * Exchange Web Services Version 2010 SP2
     *
     * @var string
     */
    const VERSION_2010_SP2 = 'Exchange2010_SP2';
    /**
     * Exchange Web Services Version 2013.
     *
     * @var string
     */
    const VERSION_2013 = 'Exchange2013';
    /**
     * Exchange Web Services Version 2013 SP1.
     *
     * @var string
     */
    const VERSION_2013_SP1 = 'Exchange2013_SP1';
    /**
     * Exchange Web Services Version Version 2016.
     *
     * @var string
     */
    const VERSION_2016 = 'Exchange2016';

    /**
     * Class Variables
     */
    protected array $_http_headers = [];
    protected array $_http_options = [
        CURLOPT_USERAGENT => 'NextCloud EWS Client',
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPAUTH => CURLAUTH_BASIC | CURLAUTH_DIGEST | CURLAUTH_NTLM,
    ];
    protected array $_soap_headers = [];
    protected array $_soap_options = [];
    protected string $_soap_description_file = DIRECTORY_SEPARATOR . 'Assets' . DIRECTORY_SEPARATOR . 'services.wsdl';

     /**
     * Location of the Exchange server.
     *
     * @var string
     */
    protected $server;
    /**
     * Username to use when connecting to the Exchange server.
     *
     * @var string
     */
    protected $username;
    /**
     * Password to use when connecting to the Exchange server.
     *
     * @var string
     */
    protected $password;
    /**
     * Exchange Web Services version that we are going to connect with
     *
     * @var string 
     */
    protected $version;
    /**
     * Timezone to be used for all requests.
     *
     * @var string
     */
    protected $timezone;
    /**
     * Exchange impersonation
     *
     * @var \OCA\EWS\Components\EWS\Type\ExchangeImpersonationType
     */
    protected $impersonation;

    /**
     * cURL resource used to make the SOAP request
     *
     * @var CurlHandle
     */
    protected $ch;
    
    /**
     * Constructor for the ExchangeWebServices class
     *
     * @param string $server        EWS Service Provider (FQDN, IPv4, IPv6)
     * @param string $username      EWS Account Id
     * @param string $password      EWS Account Password
     * @param string $version       EWS Protocol Version
     */
    public function __construct(
        $server = null,
        $username = null,
        $password = null,
        $version = self::VERSION_2010_SP2,
        $timezone = null,
        $impersonation = null
    ) {
        // Set the object properties.
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->version = $version;
        $this->timezone = $timezone;
        $this->impersonation = $impersonation;

        // construct service headers
        $this->constructSoapHeaders();
        // construct service location
        $this->constructLocation();
        // construct service authentication
        $this->constructAuthentication();
        // initilize soap client
        $so = [
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
            'classmap' => ClassMap::MAP
        ];
        $sdf = dirname(__FILE__) . $this->_soap_description_file;
        // validator (called with processResponse()) needs an XML entity loader
		$this->callWithXmlEntityLoader(function () use ($sdf, $so): void {
			parent::__construct($sdf, $so);
		});
        
    }

    /**
	 * @returns mixed returns the result of the callable parameter
	 */
	private function callWithXmlEntityLoader(callable $func) {
		libxml_set_external_entity_loader(static function ($public, $system) {
			return $system;
		});
		$result = $func();
		libxml_set_external_entity_loader(static function () {
			return null;
		});
		return $result;
	}

    /**
     * {@inheritdoc}
     */
    public function __doRequest($request, $location, $action, $version, $one_way = 0): null|string
    {
        // clear last headers and response
        $this->__last_headers = '';
        $this->__last_response = '';

        // evaluate if http client is initilized and location is the same
        if (!isset($this->ch) || curl_getinfo($this->ch, CURLINFO_EFFECTIVE_URL) != $location) {
            $this->ch = curl_init($location);
            curl_setopt_array($this->ch, $this->_http_options);
        }
        // set request header
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array (
                'Connection: Keep-Alive',
                'Content-Type: text/xml; charset=utf-8',
                'SOAPAction: "' . $action . '"',
                'Expect: 100-continue',
            )
        );
        // set request data
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $request);
        // execute request
        $response = curl_exec($this->ch);
        // evealuate execution errors
        $code = curl_errno($this->ch);
        if ($code > 0) {
            throw new RuntimeException(curl_error($this->ch), $code);
        }

        // evaluate http responses
        $code = (int) curl_getinfo($this->ch, CURLINFO_RESPONSE_CODE);
        if ($code > 400) {
            switch ($code) {
                case 401:
                    throw new RuntimeException('Unauthorized', $code);
                    break;
                case 403:
                    throw new RuntimeException('Forbidden', $code);
                    break;
                case 404:
                    throw new RuntimeException('Not Found', $code);
                    break;
                case 408:
                    throw new RuntimeException('Request Timeout', $code);
                    break;
                default:
                    throw new RuntimeException($code . ' Unknown Error', $code);
                    break;
            }
        }

        // Then, after your curl_exec call:
        $size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
        $this->__last_headers = substr($response, 0, $size);
        $this->__last_response = substr($response, $size);

        $this->cleanResponse();

        return $this->__last_response;
    }

    /**
     * Builds the soap headers to be included with the request.
     */
    protected function constructSoapHeaders()
    {
        // construct place holder
        $headers = [];
        // Set the schema version.
        $headers[] = new \SoapHeader(
            'http://schemas.microsoft.com/exchange/services/2006/types',
            'RequestServerVersion Version="' . $this->version . '"'
        );
        // set client time zone
        if (!empty($this->timezone)) {
            $headers[] = new \SoapHeader(
                'http://schemas.microsoft.com/exchange/services/2006/types',
                'TimeZoneContext',
                array(
                    'TimeZoneDefinition' => array(
                        'Id' => $this->timezone,
                    )
                )
            );
        }
        // set client impersonation
        if (!empty($this->impersonation)) {
            $headers[] = new \SoapHeader(
                'http://schemas.microsoft.com/exchange/services/2006/types',
                'ExchangeImpersonation',
                $this->impersonation
            );
        }
        // set headers
        self::__setSoapHeaders($headers);

    }

    protected function constructLocation()
    {
        // set service location
        self::__setLocation(self::URI_SCHEME_HTTPS . $this->server . self::URI_PATH);

    }

    protected function constructAuthentication()
    {
        // set service authentication
        $this->_http_options[CURLOPT_USERPWD] = $this->username . ':' . $this->password;

    }

    /**
     * Sets the cURL options
     *
     * @param array $options
     */
    public function setHttpOptions(array $options)
    {
        $this->_http_options += $options;
    }

    /**
     * Sets the server property
     *
     * @param string $server
     */
    public function setServer($server)
    {
        $this->server = $server;

        // reconstruct service location
        $this->constructLocation();
    }

    /**
     * Sets the user name property
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;

        // assign http options
        $this->constructAuthentication();
    }

    /**
     * Sets the password property
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;

        // assign http options
        $this->constructAuthentication();
    }

    /**
     * Sets the version property
     *
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;

        // We need to re-build the SOAP headers.
        $this->constructSoapHeaders();
    }

    /**
     * Sets the timezone to be used for all requests.
     *
     * @param string $timezone
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        // We need to re-build the SOAP headers.
        $this-constructSoapHeaders();
    }

    /**
     * Sets the impersonation property
     *
     * @param \OCA\EWS\Components\EWS\Type\ExchangeImpersonationType $impersonation
     */
    public function setImpersonation($impersonation)
    {
        $this->impersonation = $impersonation;

        // SOAP headers need to be rebuilt
        $this->constructSoapHeaders();
    }

    /**
     * {@inheritdoc}
     */
    public function __getLastRequestHeaders(): null|string
    {
        return implode("\n", $this->__last_headers) . "\n";
    }

    /**
     * Cleans the response body by stripping bad characters if instructed to.
     */
    protected function cleanResponse()
    {
        // If the option to strip bad characters is not set, then we shouldn't
        // do anything here.
        if (!$this->options['strip_bad_chars']) {
            return;
        }

        // Strip invalid characters from the XML response body.
        $count = 0;
        $this->__last_response = preg_replace(
            '/(?!&#x0?([9AD]))(&#x[0-1]?[0-9A-F];)/',
            ' ',
            $this->__last_response,
            -1,
            $count
        );

        // If the option to warn on bad characters is set, and some characters
        // were stripped, then trigger a warning.
        if ($this->options['warn_on_bad_chars'] && $count > 0) {
            trigger_error(
                'Invalid characters were stripped from the XML SOAP response.',
                E_USER_WARNING
            );
        }
    }

}
