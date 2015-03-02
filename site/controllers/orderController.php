<?php
include 'controllers/crudController.php';
include 'models/order.php'; 

class OrderController extends CrudController {

	public function __construct(IContext $context) {
		parent::__construct($context);  
	}

	protected function getPagename(){
		return 'Orders';
	}

	protected function getTemplateForNew () {
		return 'html/forms/adminCategoryNew.html';
	}
	
	protected function getTemplateForEdit () {
		return 'html/forms/adminCategoryEdit.html';
	}
	
	protected function getTemplateForDelete () {
		return 'html/forms/adminCategoryDelete.html';
	}
	
	protected function getTemplateForView () {
		return 'html/forms/adminOrderView.html';
	}

	protected function createModel($id) {
		return new OrderModel($this->getDB(),$id);
	}
	protected function getModelData($model) {
		$this->setField('id', $model->getOrderId());
		$this->setField('date',$model->getOrderDate());
		$this->setField('success',$model->getOrderIsSuccess());
	}
	protected function getFormData() {
		
	}
	protected function updateModel($model) {
		
	}
	protected function deleteModel($model) {
		
	}	
}
?>
