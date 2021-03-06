<?php

/*
 * This file is part of the CRUD Admin Generator project.
 *
 * Author: Jon Segador <jonseg@gmail.com>
 * Web: http://crud-admin-generator.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class queryData {
	public $start;
	public $recordsTotal;
	public $recordsFiltered;
	public $data;

	function queryData() {
	}
}

use Silex\Application;

$app = new Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/../views',
));
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
	'translator.messages' => array(),
));
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(

		'dbs.options' => array(
			'db' => array(
				'driver'   => 'pdo_mysql',
				'dbname'   => 'webinar',
				'host'     => 'localhost',
				'user'     => 'root',
				'password' => '',
				'charset'  => 'utf8',
			),
		)
));

$app['asset_path'] = 'http://localhost/webinarr/resources';
$app['debug'] = true;
	// array of REGEX column name to display for foreigner key insted of ID
	// default used :'name','title','e?mail','username'
$app['usr_search_names_foreigner_key'] = array();
$app['swiftmailer.options'] = array(
    'host' => 'smtp.gmail.com',
    'port' => 80,
    'username' => '',
    'password' => '',
    'encryption' => 'tls',
    'auth_mode' => null
    );

$app['swiftmailer.use_spool'] = false;
$app->register(new Silex\Provider\SwiftmailerServiceProvider());

return $app;
