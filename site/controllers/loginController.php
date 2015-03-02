<?php
/*

   
   Sample Login controller for a single person
   ==========================================
*/
include 'lib/abstractController.php';
include 'lIb/view.php';

class LoginController extends AbstractController {

	public function __construct(IContext $context) {
		parent::__construct($context);
	}
	protected function getView($isPostback) {
	
		$user=$this->getContext()->getUser();
		$email='';
		$password='';
		$comment='';	
		if ($isPostback) {	
			$email=$this->getInput('email');		// Now XSS safe
			$password=$this->getInput('password');
			
			if ($user->isValidLogin($this->getContext()->getDB(),$email, $password)) {
				$user->login($email, $password);
				$this->redirectTo('admin/categories','Welcome '.$user->getName());
				return null;
			} else {
				$comment='Your credentials were rejected, please try again';
			}	
		}	
		$view = new View($this->getContext());
		$view->setModel($user);
		$view->setTemplate('html/masterPage.html');
		$view->setTemplateField('pagename','Login');
		$view->setSubviewTemplate('html/forms/login.html');
		$view->setSubviewField('email',$email);
		$view->setSubviewField('password',$password);
		$view->setSubviewField('message',$comment);
		
		return $view;
	}	
}
?>
