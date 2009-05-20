<?php
require 'glue.php';

$glue = new Glue('jdp', 'freestyle');
$response = $glue->user->friends(array(
  'userId' => 'jdp'
));

echo '<pre>';
echo print_r(simplexml_load_string($response));
echo '</pre>';
?>
