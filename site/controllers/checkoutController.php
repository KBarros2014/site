<?php
//include 'models/myShoppingCart.php';
include 'models/orderModel.php';
include 'lib/abstractController.php';
include 'lib/view.php';
include 'lib/tableView.php';

class CheckoutController extends AbstractController {
	private $context;
	private $subviewTemplate;
	
	public function __construct($context) {
		parent::__construct($context);
		$this->context=$context;
	}
	protected function getView($isPostback) {
		$cart = new ShoppingCart( $this->context);
		// testing only
		$cart->delete();
		$cart = new ShoppingCart( $this->context);
	
		$cart->addItem (new ShoppingCartItem(1,20,45.95));
		$cart->addItem (new ShoppingCartItem(1,10,45.95));
		
		$cart->setCustomerId(1); // tests data
		// end test patches
		

        $uri=$this->getURI();
		$action=$uri->getPart();
		switch ($action) {
			case '':
				return $this->handleBlank($isPostback);
			case 'delivery':
			//	$this->subviewTemplate = $this->getTemplateForEdit();
				return $this->handleDelivery($isPostback,$uri->getID());	
			case 'payment':
				//$this->subviewTemplate = $this->getTemplateForView();
				return $this->handlePayment($isPostback,$uri->getID());	
			case 'final':
				//$this->subviewTemplate = $this->getTemplateForDelete();
				return $this->processOrder($isPostback,$uri->getID());	
			default:
				throw new InvalidRequestException ("Invalid action in URI");
		}	
	}
	public function  handleBlank($postback, $id =null){
	   //$this->subviewTemplate = 'html template to show cart';
	      if (!$postback) {
			// subclass will call setfield for each field
           return $this->createView($id); 			// form with data from DB (or blank if ID null)		
		  
			}
		}
		// display cart
	//	return 'html/productPanel.html';
		// user confirms they want to purchase (link to /delivery
	
	public function handleDelivery() {
	    
	     return 'ddldld.html';
	}
	public function handlePayment()  {
	
	}
	public function processOrder()  {
			OrderModel::createFromCart($this->context->getDB(),$cart);
	
	
	}
	protected function getPagename(){
		return 'checkout';
	
	}
	
	private function createView($id) {
		$view=new View($this->getContext());
		
		$view->setTemplate('html/productPanel.html');
		$view->setTemplateField('pagename',$this->getPagename());
		$view->setSubviewTemplate($this->subviewTemplate);		

		
		
		return $view;
	}
	
	
	}
?>