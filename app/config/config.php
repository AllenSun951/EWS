<?php


$dps='(DESCRIPTION =(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.18.47.200)(PORT = 1521)))'
	 . '(CONNECT_DATA = (SERVICE_NAME = ORCL.11gR2)))';


return new \Phalcon\Config(array(
    'database' => array(  
		'dbname' => $dps,
		'username' => 'jwfzxt',
		'password' => 'fz2015',
		'charset' => 'AL32UTF8',  
	),
    'application' => array(
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'modelsDir'      => __DIR__ . '/../../app/models/',
        'viewsDir'       => __DIR__ . '/../../app/views/',
        'pluginsDir'     => __DIR__ . '/../../app/plugins/',
        'libraryDir'     => __DIR__ . '/../../app/library/',
        'cacheDir'       => __DIR__ . '/../../app/cache/',
        'baseUri'        => '/xjyj/',
    )
));
