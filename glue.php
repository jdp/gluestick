<?php
/**
 * Gluestick, simple Glue API interface
 * @author Justin Poliey <jdp34@njit.edu>
 * @copyright 2009 Justin Poliey <jdp34@njit.edu>
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @package Gluestick
 */

/**
 * Gluestick base class
 */
abstract class Gluestick {

	protected $__username;
	protected $__password;
	protected $__method_family;
	protected $credentials;
	protected $http_status;
	protected $last_api_call;

	function __construct($username, $password, $method_family = null) {
		$this->__username = $username;
		$this->__password = $password;
		$this->__method_family = $method_family;
		$this->credentials = sprintf('%s:%s', $username, $password);
	}

	function __get($var) {
		$class_name = get_class($this);
		$ref = new $class_name($this->__username, $this->__password, $var);
		return $ref;
	}

	function __call($name, $args) {
		$fields = http_build_query((count($args) > 0) ? $args[0] : array(), '', '&');
		$method = (isset($this->__method_family)) ? sprintf('%s/%s', $this->__method_family, $name) : $name;
		$api_call = sprintf('http://api.getglue.com/v1/%s?%s', $method, $fields);
		return $this->request($api_call);
	}
	
}

/**
 * Synchronous version of Gluestick
 */
class Glue extends Gluestick {

	/**
	 * Does the API request grunt work
	 * @access private
	 * @param string $api_url The full URL of the Glue API method
	 * @return string The response
	 */
	protected function request($api_url) {
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $api_url);
		curl_setopt($curl_handle, CURLOPT_USERPWD, $this->credentials);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl_handle);
		$this->http_status = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
		curl_close($curl_handle);
		$this->last_api_call = $api_url;
		return $response;
	}

}

/**
 * Asynchronous version of Gluestick
 */
class GlueAsync extends Gluestick {

	var $handles = array();
	
	/**
	 * Does the API request grunt work
	 * @access private
	 * @param string $api_url The full URL of the Glue API method
	 * @return resource The cURL handle for the API method
	 */
	function request($api_url) {
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $api_url);
		curl_setopt($curl_handle, CURLOPT_USERPWD, $this->credentials);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
		$this->handles[] = curl_copy_handle($curl_handle);
		return $curl_handle;
	}
	
	/**
	 * Asynchronously sends cURL requests and returns their results
	 * @access public
	 * @param array $handles Array of cURL handles. Named keys are respected and returned, treated as aliases
	 * @return array The status codes and responses from the cURL handles
	 */
	function exec($handles = array()) {
		$handles = count($handles) ? $handles : $this->handles;
		$multi_handle = curl_multi_init();
		foreach ($handles as $handle) {
			curl_multi_add_handle($multi_handle, $handle);
		}
		do {
			$mrc = curl_multi_exec($multi_handle, $active);
		} while ($mrc == CURLM_CALL_MULTI_PERFORM);
		while ($active && $mrc == CURLM_OK) {
			if (curl_multi_select($multi_handle) != -1) {
				do {
					$mrc = curl_multi_exec($multi_handle, $active);
				} while ($mrc == CURLM_CALL_MULTI_PERFORM);
			}
		}
		$results = array();
		foreach ($handles as $alias => $handle) {
			$results[$alias] = array(
				'code' => curl_getinfo($handle, CURLINFO_HTTP_CODE),
				'response' => curl_multi_getcontent($handle)
			);
		}
		return $results;
	}
	
}
