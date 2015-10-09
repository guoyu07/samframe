<?php

$web_name = "Hello World!";

$smarty = array(
	'using' => true,
	'debug' => false,
	'left' => '{',
	'right' => '}',
);

$DB_CONF = array(
	"driver"=>"mysql",
	"host"=>"127.0.0.1",
	"port"=>"3306",
	"username"=>"root",
	"password"=>"123456",
	"charset"=>"utf8",
	"database"=>"test",
	"persistent"=>true,
	"unix_socket"=>"",
	"options"=>array()
);