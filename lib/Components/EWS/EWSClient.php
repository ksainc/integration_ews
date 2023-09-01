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
     * Http Transport Version
     *
     * @var string
     */
    const TRANSPORT_VERSION_1 = CURL_HTTP_VERSION_1_0;
    const TRANSPORT_VERSION_1_1 = CURL_HTTP_VERSION_1_1;
    const TRANSPORT_VERSION_2 = CURL_HTTP_VERSION_2_0;
    /**
     * Http Transport Scheme
     *
     * @var string
     */
    const TRANSPORT_STANDARD = 'http://';
    const TRANSPORT_SECURE = 'https://';

    /**
     * Http Transport Authentication Mode
     *
     * @var string
     */
    const TRANSPORT_AUTHENTICATION_BASIC = CURLAUTH_BASIC;
    const TRANSPORT_AUTHENTICATION_DIGEST = CURLAUTH_DIGEST;
    const TRANSPORT_AUTHENTICATION_NTLM = CURLAUTH_NTLM;
    const TRANSPORT_AUTHENTICATION_OAUTH = CURLAUTH_BEARER;

    /**
     * Soap Messaging Version
     *
     * @var string
     */
    const MESSAGE_VERSION_1_1 = SOAP_1_1;
    const MESSAGE_VERSION_1_2 = SOAP_1_2;

    /**
     * Exchange Web Services Version
     *
     * @var string
     */
    const SERVICE_VERSION_2007 = 'Exchange2007';
    const SERVICE_VERSION_2007_SP1 = 'Exchange2007_SP1';
    const SERVICE_VERSION_2009 = 'Exchange2009';
    const SERVICE_VERSION_2010 = 'Exchange2010';
    const SERVICE_VERSION_2010_SP1 = 'Exchange2010_SP1';
    const SERVICE_VERSION_2010_SP2 = 'Exchange2010_SP2';
    const SERVICE_VERSION_2013 = 'Exchange2013';
    const SERVICE_VERSION_2013_SP1 = 'Exchange2013_SP1';
    const SERVICE_VERSION_2016 = 'Exchange2016';

    /**
     * Transport Type
     *
     * @var string
     */
    protected string $_transport_mode = self::TRANSPORT_SECURE;

    /**
     * Transport Path
     *
     * @var string
     */
    protected string $_transport_path = '/EWS/Exchange.asmx';

    /**
     * Class Variables
     */
    protected array $_http_header = [
        'Connection' => 'Connection: Keep-Alive',
        'Content-Type' => 'Content-Type: text/xml; charset=utf-8',
    ];
    protected array $_http_options = [
        CURLOPT_USERAGENT => 'NextCloud EWS Client',
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_POST => true,
    ];
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
     * @var AuthenticationBasic|AuthenticationBearer
     */
    protected $authentication;
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
     * cURL resource used to make the request
     *
     * @var CurlHandle
     */
    protected $_client;
    
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
        $authentication = null,
        $version = self::SERVICE_VERSION_2010_SP2,
        $timezone = null,
        $impersonation = null
    ) {
        // Set the object properties.
        $this->server = $server;
        $this->authentication = $authentication;
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
    public function __doRequest($request, $location, $action, $version, $one_way = 0): null|string {
        // clear last headers and response
        $this->__last_headers = '';
        $this->__last_response = '';

        // evaluate if http client is initilized and location is the same
        if (!isset($this->_client) || curl_getinfo($this->_client, CURLINFO_EFFECTIVE_URL) != $location) {
            $this->_client = curl_init($location);
            curl_setopt_array($this->_client, $this->_http_options);
        }
        // set request header
        $header = array_merge(array_values($this->_http_header), array (
            'SOAPAction: "' . $action . '"',
        ));
        curl_setopt($this->_client, CURLOPT_HTTPHEADER, $header);
        // set request data
        curl_setopt($this->_client, CURLOPT_POSTFIELDS, $request);
        // execute request
        $response = curl_exec($this->_client);
        // evealuate execution errors
        $code = curl_errno($this->_client);
        if ($code > 0) {
            throw new RuntimeException(curl_error($this->_client), $code);
        }

        // evaluate http responses
        $code = (int) curl_getinfo($this->_client, CURLINFO_RESPONSE_CODE);
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
            }
        }

        // Then, after your curl_exec call:
        $size = curl_getinfo($this->_client, CURLINFO_HEADER_SIZE);
        $this->__last_headers = substr($response, 0, $size);
        $this->__last_response = substr($response, $size);

        return $this->__last_response;
    }

    /**
     * {@inheritdoc}
     */
    public function __getLastRequestHeaders(): null|string {
        return implode("\n", $this->__last_headers) . "\n";
    }

    /**
     * Builds the soap headers to be included with the request.
     */
    protected function constructSoapHeaders(): void {
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

    protected function constructLocation(): void {
        // set service location
        self::__setLocation($this->_transport_mode . $this->server . $this->_transport_path);

    }

    protected function constructAuthentication(): void {

        // set service basiic authentication
        if ($this->authentication instanceof AuthenticationBasic) {
            $this->_http_options[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC | CURLAUTH_DIGEST | CURLAUTH_NTLM;
            $this->_http_options[CURLOPT_USERPWD] = $this->authentication->Id . ':' . $this->authentication->Secret;
        }
        // set service bearer authentication
        if ($this->authentication instanceof AuthenticationBearer) {
            unset($this->_http_options[CURLOPT_HTTPAUTH]);
            $this->_http_header['Authorization'] = 'Authorization: Bearer ' . $this->authentication->Token;
        }
        // destroy existing client will need to be initilized again
        $this->_client = null;

    }

    public function configureTransportVersion(int $value): void {
        
        // store parameter
        $this->_http_options[CURLOPT_HTTP_VERSION] = $value;
        // destroy existing client will need to be initilized again
        $this->_client = null;

    }
    
    public function configureTransportMode(string $value): void {

        // store parameter
        $this->_transport_mode = $value;
        // destroy existing client will need to be initilized again
        $this->_client = null;
        // reconstruct service location
        $this->constructLocation();

    }

    public function configureTransportPath(string $value): void {

        // store parameter
        $this->_transport_path = $value;
        // destroy existing client will need to be initilized again
        $this->_client = null;
        // reconstruct service location
        $this->constructLocation();

    }

    public function configureTransportAgent(string $value): void {

        // store parameter
        $this->_http_options[CURLOPT_USERAGENT] = $value;
        // destroy existing client will need to be initilized again
        $this->_client = null;

    }

    public function configureTransportOptions(array $options): void {

        // store parameter
        $this->_http_options = array_replace($this->_http_options, $options);
        // destroy existing client will need to be initilized again
        $this->_client = null;

    }

    public function configureTransportVerification(bool $value): void {

        // store parameter
        $this->_http_options[CURLOPT_SSL_VERIFYPEER] = $value;
        // destroy existing client will need to be initilized again
        $this->_client = null;

    }

    /**
     * Gets the server parameter
     *
     * @return string
     */
    public function getServer(): string {
        
        // return version information
        return $this->server;

    }

    /**
     * Sets the server parameter to be used for all requests
     *
     * @param string $server
     */
    public function setServer(string $value): void {

        // store server
        $this->server = $value;
        // destroy existing client will need to be initilized again
        $this->_client = null;
        // reconstruct service location
        $this->constructLocation();

    }

    /**
     * Gets the protocol version parameter
     *
     * @return string
     */
    public function getVersion(): string {
        
        // return version information
        return $this->version;

    }

    /**
     * Sets the protocol version parameter to be used for all requests
     *
     * @param string $value
     */
    public function setVersion(string $value): void {

        // store version
        $this->version = $value;
        // We need to re-build the SOAP headers.
        $this->constructSoapHeaders();

    }

    /**
     * Gets the timezone parameter
     *
     * @return string
     */
    public function getTimezone(): string {
        
        // return timezone information
        return $this->timezone;

    }

    /**
     * Sets the timezone to be used for all requests.
     *
     * @param string $timezone
     */
    public function setTimezone(string $value): void {

        // store timezone
        $this->timezone = $value;
        // We need to re-build the SOAP headers.
        $this-constructSoapHeaders();

    }

    /**
     * Gets the impersonation parameters object
     *
     * @return \OCA\EWS\Components\EWS\Type\ExchangeImpersonationType
     */
    public function getImpersonation(): mixed {
        
        // return impersonation information
        return $this->impersonation;

    }

    /**
     * Sets the impersonation parameters to be used for all requests
     *
     * @param \OCA\EWS\Components\EWS\Type\ExchangeImpersonationType $impersonation
     */
    public function setImpersonation($value): void {

        // store impersonation
        $this->impersonation = $value;
        // SOAP headers need to be rebuilt
        $this->constructSoapHeaders();

    }

     /**
     * Gets the authentication parameters object
     *
     * @return AuthenticationBasic|AuthenticationBeare
     */
    public function getAuthentication(): AuthenticationBasic|AuthenticationBearer {
        
        // return authentication information
        return $this->authentication;

    }

     /**
     * Sets the authentication parameters to be used for all requests
     *
     * @param AuthenticationBasic|AuthenticationBearer $value
     */
    public function setAuthentication(AuthenticationBasic|AuthenticationBearer $value): void {
        
        // store parameter
        $this->authentication = $value;
        // destroy existing client will need to be initilized again
        $this->_client = null;
        // construct service authentication
        $this->constructAuthentication();

    }



}
