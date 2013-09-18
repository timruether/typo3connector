<?php

/**
 *
 */
class Tx_MagentoSync_Service_MagentoService extends \SoapClient
{

	/**
	 *
	 * @var array $classmap The defined classes
	 * @access private
	 */
	private static $classmap = array(
	);

	/**
	 *
	 * @param array $options A array of config values
	 * @param string $wsdl The wsdl file to use
	 * @access public
	 */
	public function __construct(array $options = array(), $wsdl = 'magento.wsdl')
	{
		foreach (self::$classmap as $key => $value) {
			if (!isset($options['classmap'][$key])) {
				$options['classmap'][$key] = $value;
			}
		}

		parent::__construct($wsdl, $options);
	}

	/**
	 * End web service session
	 *
	 * @param string $sessionId
	 * @access public
	 * @return boolean
	 */
	public function endSession(string $sessionId)
	{
		return $this->__soapCall('endSession', array($sessionId));
	}

	/**
	 * Login user and retrive session id
	 *
	 * @param string $username
	 * @param string $apiKey
	 * @access public
	 * @return string
	 */
	public function login($username = '', $apiKey = '')
	{
		return $this->__soapCall('login', array($username, $apiKey));
		//return parent::login($username, $apiKey);
	}

	/**
	 * Retrieve customers
	 *
	 * @param string $sessionId
	 * @param int $identifier
	 * @access public
	 * @return string
	 */
	public function typo3connectorCleancache($sessionId = '', $identifier = 0)
	{
		return $this->__soapCall('typo3connectorCleancache', array($sessionId, $identifier));
	}

}
