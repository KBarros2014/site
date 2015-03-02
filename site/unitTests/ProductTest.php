<?php
//include 'models/category.php';
include 'models/product.php';

class ProductTest extends UnitTest {
	
	function __construct($context) {
		parent::__construct($context);
	}

	protected function doTests() {
		$context=$this->getContext();
		$db=$context->getDB();

		// the following is just a place-holder - delete when tests written
		$this->assert (false,'Test not yet written');
	}
}
?>
	