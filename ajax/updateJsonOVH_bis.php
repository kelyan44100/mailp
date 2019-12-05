<?php
/* To see all details when var_dump() function used */
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);

$jsonOVH = $_POST['jsonOVH'];
//die(var_dump($jsonOVH));
file_put_contents( './../tmp/tmp_json_ovh_bis.json', $jsonOVH );
echo 'Success';
?>