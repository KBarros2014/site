<?php
include 'lib/abstractController.php';
include 'lib/view.php';
include 'lib/tableView.php';

class OrdersController extends AbstractController {

	public function __construct($context) {
		parent::__construct($context);
	}

	protected function getView($isPostback) {
		$db = $this->getDB();
		$sql = "Select * from orders";
		$rows = $db->query($sql);
		if (count($rows)==0) {
			$html='<p>There are no orders</p>';
		} else {
			$table = new TableView($rows);
			$table->setColumn('orderId','Order ID');
			$table->setColumn('orderDate','Order Date');
			$table->setColumn('orderIsSuccess','Order Success');
			$table->setColumn('action','Action',
				'&nbsp;<a href="##site##admin/order/view/<<orderId>>">View</a>'.
				'&nbsp;<a href="##site##admin/order/delete/<<orderId>>">Delete</a>');
			$html=$table->getHtml();
		}	
		$view= new View($this->getContext());	
		$view->setModel(null);
		$view->setTemplate('html/masterPage.html');
		$view->setTemplateField('pagename','Orders');
		$view->addContent($html);
		return $view;
	}
}
?>