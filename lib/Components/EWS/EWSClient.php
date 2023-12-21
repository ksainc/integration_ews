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
    const SERVICE_VERSION_2012 = 'Exchange2012';
    const SERVICE_VERSION_2013 = 'Exchange2013';
    const SERVICE_VERSION_2013_SP1 = 'Exchange2013_SP1';
    const SERVICE_VERSION_2015 = 'Exchange2015';
    const SERVICE_VERSION_2015_1005 = 'V2015_10_05';
    const SERVICE_VERSION_2016 = 'Exchange2016';
    const SERVICE_VERSION_2016_0106 = 'V2016_01_06';
    const SERVICE_VERSION_2016_0413 = 'V2016_04_13';
    const SERVICE_VERSION_2016_0713 = 'V2016_07_13';
    const SERVICE_VERSION_2016_1010 = 'V2016_10_10';

    /**
     * Transport Mode
     *
     * @var string
     */
    protected string $_transport_mode = self::TRANSPORT_SECURE;

     /**
     * Transport Location
     *
     * @var string
     */
    protected ?string $_transport_location = null;

    /**
     * Transport Path
     *
     * @var string
     */
    protected string $_transport_path = '/EWS/Exchange.asmx';

    /**
     * Transport Authentication
     *
     * @var AuthenticationBasic|AuthenticationBearer
     */
    protected $_transport_authentication = null;

    /**
     * Class Variables
     */
    protected array $_transport_header = [
        'Connection' => 'Connection: Keep-Alive',
        'Content-Type' => 'Content-Type: text/xml; charset=utf-8',
    ];

    protected array $_transport_options = [
        CURLOPT_USERAGENT => 'NextCloud EWS Client',
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_POST => true,
    ];

     /**
     * cURL resource used to make the request
     *
     * @var CurlHandle
     */
    protected $_client;

    /**
     * Retain Last Transport Request Header Flag
     *
     * @var bool
     */
    protected bool $_TransportRequestHeaderFlag = false;

    /**
     * Retain Last Transport Request Body Flag
     *
     * @var bool
     */
    protected bool $_TransportRequestBodyFlag = false;

    /**
     * Last Transport Request Header Data
     *
     * @var string
     */
    protected string $_TransportRequestHeaderData = '';

    /**
     * Last Transport Request Body Data
     *
     * @var string
     */
    protected string $_TransportRequestBodyData = '';

    /**
     * Retain Last Transport Response Header Flag
     *
     * @var bool
     */
    protected bool $_TransportRepsonseHeaderFlag = false;

    /**
     * Retain Last Transport Response Body Flag
     *
     * @var bool
     */
    protected bool $_TransportRepsonseBodyFlag = false;

    /**
     * Last Transport Response Header Data
     *
     * @var string
     */
    protected string $_TransportRepsonseHeaderData = '';

    /**
     * Last Transport Response Header Data
     *
     * @var string
     */
    protected string $_TransportRepsonseBodyData = '';

    /**
     * Exchange Web Services WSDL description file
     *
     * @var string 
     */
    protected string $_service_description_file = DIRECTORY_SEPARATOR . 'Assets' . DIRECTORY_SEPARATOR . 'services.wsdl';

    /**
     * Exchange Web Services version that we are going to connect with
     *
     * @var string 
     */
    protected string $_service_version  = self::SERVICE_VERSION_2010_SP2;

    /**
     * Timezone to be used for all requests.
     *
     * @var string
     */
    protected ?string $_client_timezone = null;

    /**
     * Exchange impersonation
     *
     * @var \OCA\EWS\Components\EWS\Type\ExchangeImpersonationType
     */
    protected $_client_impersonation = null;
    
    /**
     * Constructor for the ExchangeWebServices class
     *
     * @param string $location          EWS Service Location (FQDN, IPv4, IPv6)
     * @param string $authentication    EWS Authentication
     * @param string $version           EWS Service Version
     * @param string $timezone          EWS Client Time Zone
     * @param string $impersonate       EWS Client Impersonation
     */
    public function __construct(
        $location = null,
        $authentication = null,
        $version = self::SERVICE_VERSION_2010_SP2,
        $timezone = null,
        $impersonate = null
    ) {
        // Set the object properties.
        $this->_transport_location = $location;
        $this->_transport_authentication = $authentication;
        $this->_service_version = $version;
        $this->_client_timezone = $timezone;
        $this->_client_impersonation = $impersonate;
        // construct service location
        $this->_constructTransportLocation();
        // construct service authentication
        $this->_constructTransportAuthentication();
        // construct service headers
        $this->_constructServiceHeader();
        // initilize soap client
        $so = [
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
            'classmap' => ClassMap::MAP
        ];
        $sdf = dirname(__FILE__) . $this->_service_description_file;
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
     * Executes commands agains the service host. Soap Function Overload.
     *
     * @param string $request           Command contents (XML format)
     * @param string $location          Service location URL (https://domain/path/service)
     * @param string $action            Command action/name
     * @param string $version           Messaging Version (SOAP Version)
     * @param string $unidirectional    Command does not return response
     * 
     * @return string|null              
     */
    public function __doRequest($request, $location, $action, $version, $unidirectional = 0): null|string {

        // evaluate if http client is initilized and location is the same
        if (!isset($this->_client) || curl_getinfo($this->_client, CURLINFO_EFFECTIVE_URL) != $location) {
            $this->_client = curl_init($location);
            curl_setopt_array($this->_client, $this->_transport_options);
        }
        // set request header
        $header = array_merge(array_values($this->_transport_header), array (
            'SOAPAction: "' . $action . '"',
        ));
        curl_setopt($this->_client, CURLOPT_HTTPHEADER, $header);
        // set request data
        curl_setopt($this->_client, CURLOPT_POSTFIELDS, $request);

        // evaluate, if we are retaining request headers
        if ($this->_TransportRequestHeaderFlag) { $this->_TransportRequestHeaderData = $header; }
        // evaluate, if we are retaining request body
        if ($this->_TransportRequestBodyFlag) { $this->_TransportRequestBodyData = $request; }

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

        // extract header size
        $header_size = curl_getinfo($this->_client, CURLINFO_HEADER_SIZE);
        // evaluate, if we are retaining request headers
        if ($this->_TransportRepsonseHeaderFlag) { $this->_TransportRepsonseHeaderData = substr($response, 0, $header_size); }
        // evaluate, if we are retaining request body
        if ($this->_TransportRepsonseBodyFlag) { $this->_TransportRepsonseBodyData = substr($response, $header_size); }

        return substr($response, $header_size);
    }

    /**
     * Constructs and sets full transport location URL
     */
    protected function _constructTransportLocation(): void {

        // set service location
        self::__setLocation($this->_transport_mode . $this->_transport_location . $this->_transport_path);

    }

    /**
     * Constructs and sets transport authentication
     */
    protected function _constructTransportAuthentication(): void {

        // set service basiic authentication
        if ($this->_transport_authentication instanceof AuthenticationBasic) {
            $this->_transport_options[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC | CURLAUTH_DIGEST | CURLAUTH_NTLM;
            $this->_transport_options[CURLOPT_USERPWD] = $this->_transport_authentication->Id . ':' . utf8_decode($this->_transport_authentication->Secret);
        }
        // set service bearer authentication
        elseif ($this->_transport_authentication instanceof AuthenticationBearer) {
            unset($this->_transport_options[CURLOPT_HTTPAUTH]);
            $this->_transport_header['Authorization'] = 'Authorization: Bearer ' . $this->_transport_authentication->Token;
        }
        // destroy existing client will need to be initilized again
        $this->_client = null;

    }

    /**
     * Constructs and sets service header
     */
    protected function _constructServiceHeader(): void {

        // construct place holder
        $headers = [];
        // Set the schema version.
        $headers[] = new \SoapHeader(
            'http://schemas.microsoft.com/exchange/services/2006/types',
            'RequestServerVersion Version="' . $this->_service_version . '"'
        );
        // set client time zone
        if (!empty($this->_client_timezone)) {
            $headers[] = new \SoapHeader(
                'http://schemas.microsoft.com/exchange/services/2006/types',
                'TimeZoneContext',
                array(
                    'TimeZoneDefinition' => array(
                        'Id' => $this->_client_timezone,
                    )
                )
            );
        }
        // set client impersonation
        if (!empty($this->_client_impersonation)) {
            $headers[] = new \SoapHeader(
                'http://schemas.microsoft.com/exchange/services/2006/types',
                'ExchangeImpersonation',
                $this->_client_impersonation
            );
        }
        // set headers
        self::__setSoapHeaders($headers);

    }

    /**
     * configures service transport version (HTTP/1, HTTP/1.1, HTTP/2)
     */
    public function configureTransportVersion(int $value): void {
        
        // store parameter
        $this->_transport_options[CURLOPT_HTTP_VERSION] = $value;
        // destroy existing client will need to be initilized again
        $this->_client = null;

    }
    
    /**
     * configures service transport mode (http://, https://)
     */
    public function configureTransportMode(string $value): void {

        // store parameter
        $this->_transport_mode = $value;
        // destroy existing client will need to be initilized again
        $this->_client = null;
        // reconstruct service location
        $this->_constructTransportLocation();

    }

    /**
     * configures service location path (/path/to/service.asmx)
     * 
     * @param string $value             full path string
     */
    public function configureTransportPath(string $value): void {

        // store parameter
        $this->_transport_path = $value;
        // destroy existing client will need to be initilized again
        $this->_client = null;
        // reconstruct service location
        $this->_constructTransportLocation();

    }

    /**
     * configures client agent string (Mozilla/5.0 (X11; Linux x86_64))
     * 
     * @param string $value             full agent string
     */
    public function configureTransportAgent(string $value): void {

        // store parameter
        $this->_transport_options[CURLOPT_USERAGENT] = $value;
        // destroy existing client will need to be initilized again
        $this->_client = null;

    }

    /**
     * configures or overrides additional transport options
     * 
     * @param array $options            key/value array of options
     */
    public function configureTransportOptions(array $options): void {

        // store parameter
        $this->_transport_options = array_replace($this->_transport_options, $options);
        // destroy existing client will need to be initilized again
        $this->_client = null;

    }

    /**
     * configures secure transport verification (SSL Verification)
     * 
     * @param bool $value           ture or false flag
     */
    public function configureTransportVerification(bool $value): void {

        // store parameter
        $this->_transport_options[CURLOPT_SSL_VERIFYPEER] = $value;
        // destroy existing client will need to be initilized again
        $this->_client = null;

    }

    /**
     * Enables or disables retention of raw request headers sent
     * 
     * @param bool $value           ture or false flag
     */
    public function retainTransportRequestHeader(bool $value): void {
        $this->_TransportRequestHeaderFlag = $value;
    }

    /**
     * Enables or disables retention of raw request body sent
     * 
     * @param bool $value           ture or false flag
     */
    public function retainTransportRequestBody(bool $value): void {
        $this->_TransportRequestBodyFlag = $value;
    }

    /**
     * Enables or disables retention of raw response headers recieved
     * 
     * @param bool $value           ture or false flag
     */
    public function retainTransportResponseHeader(bool $value): void {
        $this->_TransportRepsonseHeaderFlag = $value;
    }

    /**
     * Enables or disables retention of raw response body recieved
     * 
     * @param bool $value           ture or false flag
     */
    public function retainTransportResponseBody(bool $value): void {
        $this->_TransportRepsonseBodyFlag = $value;
    }

    /**
     * returns last retained raw request header sent
     * 
     * @return string
     */
    public function discloseTransportRequestHeader(): string {
        return $this->_TransportRequestHeaderData;
    }

    /**
     * returns last retained raw request body sent
     * 
     * @return string
     */
    public function discloseTransportRequestBody(): string {
        return $this->_TransportRequestBodyData;
    }

    /**
     * returns last retained raw response header recieved
     * 
     * @return string
     */
    public function discloseTransportResponseHeader(): string {
        return $this->_TransportRepsonseHeaderData;
    }

    /**
     * returns last retained raw response body recieved
     * 
     * @return string
     */
    public function discloseTransportResponseBody(): string {
        return $this->_TransportRepsonseBodyData;
    }

    /**
     * Gets the service location
     *
     * @return string
     */
    public function getLocation(): string {
        
        // return version information
        return $this->_transport_location;

    }

    /**
     * Sets the service location parameter to be used for all requests
     *
     * @param string $value
     */
    public function setLocation(string $value): void {

        // store server
        $this->_transport_location = $value;
        // destroy existing client will need to be initilized again
        $this->_client = null;
        // reconstruct service location
        $this->_constructTransportLocation();

    }

    /**
     * Gets the service authentication parameters object
     *
     * @return AuthenticationBasic|AuthenticationBeare
     */
    public function getAuthentication(): AuthenticationBasic|AuthenticationBearer {
        
        // return authentication information
        return $this->_transport_authentication;

    }

    /**
     * Sets the service authentication parameters to be used for all requests
     *
     * @param AuthenticationBasic|AuthenticationBearer $value
     */
    public function setAuthentication(AuthenticationBasic|AuthenticationBearer $value): void {
        
        // store parameter
        $this->_transport_authentication = $value;
        // destroy existing client will need to be initilized again
        $this->_client = null;
        // construct service authentication
        $this->_constructTransportAuthentication();

    }

    /**
     * Gets the protocol version parameter
     *
     * @return string
     */
    public function getVersion(): string {
        
        // return version information
        return $this->_service_version;

    }

    /**
     * Sets the protocol version parameter to be used for all requests
     *
     * @param string $value
     */
    public function setVersion(string $value): void {

        // store version
        $this->_service_version = $value;
        // We need to re-build the SOAP headers.
        $this->_constructServiceHeader();

    }

    /**
     * Gets the timezone parameter
     *
     * @return string
     */
    public function getTimezone(): string {
        
        // return timezone information
        return $this->_client_timezone;

    }

    /**
     * Sets the timezone to be used for all requests.
     *
     * @param string $timezone
     */
    public function setTimezone(string $value): void {

        // store timezone
        $this->_client_timezone = $value;
        // We need to re-build the SOAP headers.
        $this-_constructServiceHeader();

    }

    /**
     * Gets the impersonation parameters object
     *
     * @return \OCA\EWS\Components\EWS\Type\ExchangeImpersonationType
     */
    public function getImpersonation(): mixed {
        
        // return impersonation information
        return $this->_client_impersonation;

    }

    /**
     * Sets the impersonation parameters to be used for all requests
     *
     * @param \OCA\EWS\Components\EWS\Type\ExchangeImpersonationType $value
     */
    public function setImpersonation($value): void {

        // store impersonation
        $this->_client_impersonation = $value;
        // SOAP headers need to be rebuilt
        $this->_constructServiceHeader();

    }

}
