<?php

	session_start();
	
	require_once 'classes/EcfError.php';
	require_once 'classes/Tools.php';
	require_once 'classes/DBTools.php';
	require_once 'classes/Product.php';
	require_once 'classes/Category.php';
	require_once 'classes/Cart.php';
	require_once 'classes/Order.php';
	require_once 'classes/User.php';
	require_once 'classes/Renderer.php';

	define('__DEBUG__', true);
	
	/* Defines for DataBase */
	define('__DB__', '');
	define('__DB_PREFIX__', '');
	define('__DB_HOST__', '127.0.0.1'); // <=== Your DB username !!!
	define('__DB_USER__', 'root'); // <=== Your DB password !!!
	define('__DB_PASSWORD__', '');
	
	/* Defines for mail sending */
	define('__MAIL_HOST__', 'smtp.gmail.com');
	define('__MAIL_PORT__', 465);
	define('__MAIL_USERNAME__', 'example@gmail.com'); // <=== Your gmail email (username), it won't work with example@gmail.com !!!
	define('__MAIL_PASSWORD__', ''); // <=== Your gmail app' password !!!
	define('__MAIL_FROM__', '');
	define('__MAIL_FROM_NAME__', 'Mr Toto');
	define('__MAIL_OBJECT__', 'Your bill order ' . time());
	define('__MAIL_BODY__', 'It\'s is a fake mail for billing information, but we could put some variables here with concatenation (like timestamp thank to time() function in title of this mail) !');
	define('__MAIL_TO__', '');
	
	/* Defines for logs */
	if (!Tools::htmlsession(${$id = 'id'}))
		$id = 'Unknown user';
	define('__LOG_CONTENT_SUCCESS__', '[SUCCESS] Time : ' . time() . ' User Id : ' . $id . PHP_EOL);
	define('__LOG_CONTENT_FAILURE__', '[FAILURE] Time : ' . time() . ' User Id : ' . $id . PHP_EOL);
	define('__LOG_FILE__', 'logs/log.txt');
		
	if (__DEBUG__)
	{
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}

?>
			<?php
				Renderer::getInstance()->display();
			?>
