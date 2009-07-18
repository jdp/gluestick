<?php
class GlueException extends Exception {
	var $code;
	var $name;
}

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

class Glue extends Gluestick {

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

class GlueAsync extends Gluestick {

	var $handles = array();
	
	function request($api_url) {
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $api_url);
		curl_setopt($curl_handle, CURLOPT_USERPWD, $this->credentials);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
		$this->handles[] = curl_copy_handle($curl_handle);
		return $curl_handle;
	}
	
	function exec() {
		$handles = count(func_get_args()) ? func_get_args() : $this->handles;
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
		foreach ($handles as $handle) {
			$results[] = array(
				'code' => curl_getinfo($handle, CURLINFO_HTTP_CODE),
				'response' => curl_multi_getcontent($handle)
			);
		}
		return $results;
	}
	
}
?>
