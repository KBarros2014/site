<?php
/*

   Sample CRUD controller for a list of categories
   ===============================================
   
*/
include 'lib/abstractController.php';
include 'lib/view.php';
include 'lib/tableView.php';

class CustomersController extends AbstractController {

	public function __construct($context) {
		parent::__construct($context);
	}
	protected function getView($isPostback) {
		$db=$this->getDB();
	
		$sql="select customerId, customerFirstName, customerLastName, customerAddress
, customerCity, customerPostCode, customerEmail, isBusinessAccount from customers order by customerFirstName asc";
		$rows=$db->query($sql);
		if (count($rows)==0) {
			$html='<p>There are no customers</p>';
			
		} else {
			$table = new TableView($rows);
			$table->setColumn('customerFirstName','Customer first name');
			$table->setColumn('customerLastName','Customer last name');
			$table->setColumn('customerEmail','Customer email');
			$table->setColumn('action','Action',
				'&nbsp;<a href="##site##admin/customer/view/<<customerId>>">View</a>'.
				'&nbsp;<a href="##site##admin/customer/edit/<<customerId>>">Edit</a>'.
				'&nbsp;<a href="##site##admin/customer/delete/<<customerId>>">Delete</a>');
			$html=$table->getHtml();
			$html.='<p><a href="##site##admin/customer/new">Add a new customer</a></p>';
		
			
		}	
		$view= new View($this->getContext());	
		$view->setModel(null);
		$view->setTemplate('html/masterPage.html');
		$view->setTemplateField('pagename','Users');
		echo "kdkd";//that s me playkb 
		$view->addContent($html);
		return $view;
	}
}

?>
