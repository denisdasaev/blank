<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'BlankProject',
//	'sourceLanguage'=>'en',
  'language'=>'ru',

	// preloading 'log' component
	'preload'=>array('log'),

  'import'=>array(
    'application.models.*',
    'application.models.core.*',
    'application.components.*',
//    'application.components.ajax.*',
  ),

  // application components
	'components'=>array(
		'db'=>array(
      'connectionString' => 'mysql:host=localhost;dbname=blank',
      'emulatePrepare' => true,
      'username' => 'root',
      'password' => '',
      'charset' => 'utf8',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);