<?php
/*

   
   Sample CRUD controller for a product category
   =============================================

   The following URI patterns are handled by this controller: 
   
   /admin/category/new         	create a new category
   /admin/category/edit/nn		edit category nn
   /admin/category/delete/nn	delete category nn
   /admin/category/view/nn      view category nn
   
   (nn is the category ID)
   
   Note that most of the logic is in the parent CRUD controller
   Here, We're just implementing the category specific stuff
*/

include 'controllers/crudController.php';
include 'models/category.php';

class CategoryController extends CrudController {

	public function __construct(IContext $context) {
		parent::__construct($context);
	}

	protected function getPagename(){
		return 'Categories';
		//echo ' for adding categories';
	}
	
	// the following methods are the must-overrides in the Crud controller
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
		return 'html/forms/adminCategoryView.html';
	}
	protected function createModel($id) {
		return new CategoryModel($this->getDB(),$id);
	}
	protected function getModelData($model) {
		$this->setField('name', $model->getName());
		$this->setField('description',$model->getDescription());		
	}
	protected function getFormData() {
		$name=$this->getInput('name');
		$this->setField('name', $name);
		$error=CategoryModel::errorInName($name);
		if ($error!==null) {
			$this->setError ('name',$error);
		}
		$description=$this->getInput('description');
		$this->setField('description', $description);
		$error=CategoryModel::errorInDescription($description);
		if ($error!==null) {
			$this->setError ('description',$error);
		}
	}
	protected function updateModel($model) {
		$name=$this->getField('name');
		$description=$this->getField('description');
		
		$model->setName($name);
		$model->setDescription($description);	
		$model->save();
		$this->redirectTo('admin/categories',"Category '$name' has been saved");
	}
	protected function deleteModel($model) {
		$name=$model->getName();
		$model->delete();
		$this->redirectTo('admin/categories',"Category '$name' has been deleted");
	}	
}
?>
