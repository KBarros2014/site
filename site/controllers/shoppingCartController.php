
<?php 

/*so lets get started with controller 
 based on ML controllers sample available on moodle
  the uri /patterns deal t int his controller
  /shoppingCart/new
 
 */
include 'lib/AbstractController.php';
include 'lib/view.php';
include 'lib/tableView.php';
 class ShoppingCartController extends AbstractController {
	private $context;
	private $subviewTemplate;
	public function __construct($context) {
		parent::__construct($context);
		$this->context=$context;
	}
 
	protected function getView($isPostback) {
	  $uri=$this->getURI();
	  $action=$uri->getPart();
		switch ($action) {
			case 'view':
				//$this->subviewTemplate = $this->getTemplateForView();
				return $this->handleView($isPostback,$uri->getID());	
			case 'add':
				//$this->subviewTemplate = $this->getTemplateForEdit();
				return $this->handleAddToCart($isPostback,$uri->getID());	
			case 'remove':
				//$this->subviewTemplate = $this->getTemplateForDelete();
				return $this->handleDelete($isPostback,$uri->getID());	
			default:
				throw new InvalidRequestException ("Invalid action in URI");
		}	
		
		public function  handleView($postback, $id =null){
	   //$this->subviewTemplate = 'html template to show cart';
	      if (!$postback) {
			// subclass will call setfield for each field
           return $this->createView($id); 			// form with data from DB (or blank if ID null)		
		  
			}
		}
		private function createView($id) {
		$view=new View($this->getContext());
		
		$view->setTemplate('html/productPanel.html');
		$view->setTemplateField('pagename',$this->getPagename());
		$view->setSubviewTemplate($this->subviewTemplate);		

		
		
		return $view;
	}
	protected function getPagename(){
		return  'shoppingCart';
	
	}
	protected function getTemplateForView () {
		return 'html/cart/productPanel.html';
	}
    protected function getPagename(){
		return 'ShoppingCart';
		//echo ' for adding categories';
	}
	protected function getTemplateForDelete () {
		return 'html/forms/adminCategoryDelete.html';
	}
		}
		}