<?php
	include 'lib/interfaces.php';
	include 'lib/context.php';	
	include 'models/user.php';

	// version check 	
	if ((!function_exists('version_compare')) ||
	    (version_compare(PHP_VERSION, '5.3.0') < 0) ) {
		echo "This software requires PHP 5.3 or higher\n";
		exit;
	}

	try {
		// common stuff can go here
		date_default_timezone_set('Pacific/Auckland');

		$context=Context::createFromConfigurationFile("website.conf");
		$context->setUser(new User($context));

		// getController will throw an exception if the request path is invalid
		// or the user is not authorised for access		
		$controller=getController($context);
		// the approach below is called "convention over configuration"
		// rather than configuring separate class names and routes, we base
		// the pattern on naming and site organisation conventions
		$controllerPath='controllers/'.strtolower($controller).'Controller.php';
		$controllerClass=$controller.'Controller';
		require $controllerPath;
		$actor = new $controllerClass($context);
		$actor->process();
		
	} catch (LoginException $ex) {
		echo 'TODO: please login or timeout message<br/>';
		
	} catch (Exception $ex) {
		logException ($ex);
		echo 'Page not found<br/>';
		// It is bad security practice to reveal exceptions to users
		// We log the exception and give a looser description of
		// the problem to the user.
		// We'll make the output prettier later
		exit;
	}
		
	function logException ($ex) {
		// do nothing for now
		echo "Should log: ".$ex->getMessage();
	}
	
	// This is a very simple router
	// We just match the URI to a stub of the controller name
	function getController($context) {
		$uri=$context->getURI();
		$path=$uri->getPart();
		switch ($path) {
			case 'admin':
				return getAdminController($context);
			case '':
				$uri->prependPart('home');
				return 'Static';
			case 'static':
				return 'Static';
			case 'login':
				return 'Login';
			case 'logout':
				return 'Logout';
			case "checkout":
				return "Checkout";
			case "myShoppingCart":
			      return "ShoppingCart";
			default:
				throw new InvalidRequestException ("No such page ");
		}
	}

	// This is a very simple router
	// We just match the URI to a stub of the controller name
	function getAdminController($context) {
		if (!$context->getUser()->isAdmin() ) {
			throw new InvalidRequestException('Administrator access required for this page');
		}
		$uri=$context->getURI();
		$path=$uri->getPart();
		switch ($path) {
		    case 'categories':
				return 'Categories';
		    case 'category':
				return 'Category';
		    case 'products':
				return 'Products';
		    case 'product':
				return 'Product';
			case 'customers':
				return 'Customers';
		    case 'customer':
				return 'Customer';

			case 'orders':
				return 'Orders';
			case 'order':
				return 'Order';

			case 'order':
				return 'Order';
			case 'orders':
				return 'Orders';
			case 'checkout':
				return 'Checkout';
		  	case 'shoppingCart';
			     return 'ShoppingCart';
		    case 'ShoppingCart':
			     return 'ShoppingCart';

			default:
				throw new InvalidRequestException ('No such page');
		}
	}
	
?>


