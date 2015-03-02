<?php
/*
  
    CRUD controller for a list of categories
   ===============================================
   
*/
include 'lib/abstractController.php';
include 'lib/view.php';
include 'lib/tableView.php';


class ProductsController extends AbstractController {

	public function __construct($context) {
		parent::__construct($context);
	}
	protected function getView($isPostback) {
		$db=$this->getDB(); 
		$sql="select productID, productName from products order by productName asc";
		$rows=$db->query($sql);
		if (count($rows)==0) {
			$html='<p>There are no Products</p>'; 
		
		} else {
			$table = new TableView($rows);
			$table->setColumn('productName','Product name'); 
			$table->setColumn('action','Action',
				'&nbsp;<a href="##site##admin/product/view/<<productID>>">View</a>'.
				'&nbsp;<a href="##site##admin/product/edit/<<productID>>">Edit</a>'.
				'&nbsp;<a href="##site##admin/product/delete/<<productID>>">Delete</a>'); 
			$html=$table->getHtml();
			$html.='<p><a href="##site##admin/product/new">Add a new product</a></p>';
		}	
		$view= new View($this->getContext());	
		$view->setModel(null);
		$view->setTemplate('html/masterPage.html');
		$view->setTemplateField('pagename','Products');
		$view->addContent($html);
		return $view;
	}
}
?>
