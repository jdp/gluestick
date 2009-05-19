<?php
class Glue {
  private $__username;
  private $__password;
  private $__method_family;
  private $credentials;
  private $http_status;
  private $last_api_call;

  function __construct($username, $password, $method_family = null) {
    $this->__username = $username;
    $this->__password = $password;
    if (isset($method_family)) {
      $this->__method_family = $method_family;
    }
    else {
      unset($this->__method_family);
    }
    $this->credentials = sprintf('%s:%s', $username, $password);
  }

  function __get($var) {
    $class_name = get_class($this);
    $ref = new $class_name($this->__username, $this->__password, $var);
    return $ref;
  }

  function __call($name, $args) {
    $fields = http_build_query((count($args) > 0) ? $args[0] : array());
    $method = (isset($this->__method_family)) ? sprintf('%s/%s', $this->__method_family, $name) : $name;
    $api_call = sprintf('http://api.getglue.com/v1/%s?%s', $method, $fields);
    return $this->request($api_call);
  }

  function request($api_url) {
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
?>
