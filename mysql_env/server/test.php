<?php
$key = openssl_get_publickey('file:///application/public.pem');
$details = openssl_pkey_get_details($key);

var_dump(is_resource($key));

?>
