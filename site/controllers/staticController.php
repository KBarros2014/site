<?php

/*
 
   
 r
   ======================
   
  
   
*/
include 'lib/abstractController.php';
include 'models/static.php';
include 'views/static.php';

class StaticController extends AbstractController {
	private $path;
	public function __construct($context) {
		parent::__construct($context);
		$this->path=$context->getURI()->getRemainingParts();
	}
	
	protected function getView($isPostback) {
	
		$db=$this->getDB();
		$model = new StaticModel($db, $this->getContext(), $this->path);
		// create output
		$view=new StaticView($this->getContext());
	
		$view->setModel($model);
		$view->setTemplate('html/masterPage.html');
		$path=explode("/",$this->path);
		$title=ucwords(implode(' ',$path));
		$view->setTemplateField('pagename',$title);
		return $view;
	}
}
?>
