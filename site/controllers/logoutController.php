<?php
/*
 
   ========================
*/
include 'lib/abstractController.php';

class LogoutController extends AbstractController {

	public function __construct(IContext $context) {
		parent::__construct($context);
	}
	
	protected function getView($isPostback) {
		$user=$this->getContext()->getUser();
		$message=$user->getName().' logged out.';
		$user->logout();
		$this->redirectTo('',$message);
		return null;
	}	
}
?>
