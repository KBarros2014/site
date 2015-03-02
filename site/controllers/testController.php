<?php
/*
  

   ======================
   
   we'll work with a list of tests
   each test is defined by a file in the tests directory
*/
include 'lib/abstractController.php';
include 'models/tests.php';
include 'views/tests.php';
include 'lib/unitTest.php';

class TestController extends AbstractController {
	
	public function __construct($context) {
		parent::__construct($context);
	}
	
	protected function getView($isPostback) {
		$db=$this->getDB();
		$model = new TestsModel($db, $this->getContext());
		$model->runTests();
		// create output
		$view=new TestsView($this->getContext());
		$view->setModel($model);
		$view->setTemplate('html/masterPage.html');
		$view->setTemplateField('pagename','Automated tests');
		return $view;
	}
}
?>
