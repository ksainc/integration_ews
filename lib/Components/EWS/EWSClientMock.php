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
class EWSClientMock extends \SoapClient
{

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
     * Transport Location
     *
     * @var string
     */
    protected ?string $_transport_location = null;

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
    protected $_client_impersonate = null;
    
    /**
     * Constructor for the ExchangeWebServices class
     *
     * @param string $location          EWS Service Location (File Path)
     * @param string $version           EWS Service Version
     * @param string $timezone          EWS Client Time Zone
     * @param string $impersonate       EWS Client Impersonation
     */
    public function __construct(
        $location = null,
        $version = self::SERVICE_VERSION_2010_SP2,
        $timezone = null,
        $impersonate = null
    ) {
        // Set the object properties.
        $this->_transport_location = $location;
        $this->_service_version = $version;
        $this->_client_timezone = $timezone;
        $this->_client_impersonate = $impersonate;
        // construct service location
        $this->_constructTransportLocation();
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

        // retrieve the action
        $action = substr($action, strrpos($action, '/') + 1);
        // evaluate is location exists
        if (!is_dir($location)) {
            throw new RuntimeException("The mock location specified does not exist");
        }
        // evaluate if response exists
        if (!file_exists($location . DIRECTORY_SEPARATOR . 'Response-' . $action . '.xml')) {
            
            throw new RuntimeException("The mock response file for the specified action does not exist");
        }
        // deposit request message
        file_put_contents($location . DIRECTORY_SEPARATOR . 'Request-' . $action . '.xml', $request);
        // retrieve response message
        $response = file_get_contents($location . DIRECTORY_SEPARATOR . 'Response-' . $action . '.xml');
        // return response message
        return $response;

    }

    /**
     * Constructs and sets full transport location URL
     */
    protected function _constructTransportLocation(): void {

        // set service location
        self::__setLocation($this->_transport_location);

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
        // set client impersonate
        if (!empty($this->_client_impersonate)) {
            $headers[] = new \SoapHeader(
                'http://schemas.microsoft.com/exchange/services/2006/types',
                'ExchangeImpersonation',
                $this->_client_impersonate
            );
        }
        // set headers
        self::__setSoapHeaders($headers);

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
        return $this->_client_impersonate;

    }

    /**
     * Sets the impersonation parameters to be used for all requests
     *
     * @param \OCA\EWS\Components\EWS\Type\ExchangeImpersonationType $impersonation
     */
    public function setImpersonation($value): void {

        // store impersonation
        $this->_client_impersonate = $value;
        // SOAP headers need to be rebuilt
        $this->_constructServiceHeader();

    }

}
