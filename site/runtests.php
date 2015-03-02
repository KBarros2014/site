<?php
	include "lib/interfaces.php";
	include 'lib/context.php';
	include 'controllers/testController.php';
	include 'models/user.php';
	
	try {
		date_default_timezone_set('Pacific/Auckland');
		$context=Context::createFromConfigurationFile("website.conf");
		$context->setUser(new User($context));
		$tests = new TestController($context);
		$tests->process();
	} catch (Exception $ex) {
		echo $ex->getMessage();
	}	
?>
	