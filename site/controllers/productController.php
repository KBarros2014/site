<?php
/*
 CRUD controller for a product
   =============================================

   The following URI patterns are handled by this controller: 
   
   /admin/product/new         	create a new product
   /admin/Product/edit/nn		edit product nn
   /admin/product/delete/nn	delete product nn
   /admin/product/view/nn      view product nn
   
   (nn is the product ID)
   
   Note that most of the logic is in the parent CRUD controller
   Here, We're just implementing the product specific stuff
*/

include 'controllers/crudController.php';
include 'models/product.php'; 
include 'lib/listSelectionView.php';

class ProductController extends CrudController {

	public function __construct(IContext $context) {
		parent::__construct($context);  
	}

	protected function getPagename(){
		return 'Products'; 
	} 

	// the following methods are the must-overrides in the Crud controller
	protected function getTemplateForNew () {
		return 'html/forms/adminProductNew.html'; 
	}
	protected function getTemplateForEdit () {
		return 'html/forms/adminProductEdit.html'; 
	}
	protected function getTemplateForDelete () {
		return 'html/forms/adminProductDelete.html';
	}
	protected function getTemplateForView () {
		return 'html/forms/adminProductView.html';
	}
	protected function createModel($id) {
		return new ProductModel($this->getDB(),$id);
	}
	private function getCategoryList($selectedID) {
		$db=$this->getDB();
		$sql='select catID, catName from categories order by catName';
		$rowset=$db->query($sql);
		$list = new ListSelectionView($rowset);
		$list->setFormName ('catID');
		$list->setIdColumn ('catID');
		$list->setValueColumn ('catName');
		$list->setSelectedId ($selectedID);	
		return $list->getHtml();
	}
	protected function getModelData($model) {
		$this->setField('name', $model->getProductName());
		$this->setField('description',$model->getProductDescription());	//	for the tests
		$this->setField('price',$model->getProductPrice());		
		$this->setField('picture',$model->getProductPic());	
		$this->setField('catID', $model->getCategory()->getName().'('.$model->getCategoryId().')');
		$this->setField('categoryList', $this->getCategoryList($model->getCategoryId()));
	}
	
	protected function getFormData() {
		$price=$this->getInput('price');
		$this->setField('price', $price);
			$error=ProductModel::errorInProductPrice($price);
		if ($error!==null) {
			$this->setError ('price',$error);
		}
	
		$name=$this->getInput('name');
		$this->setField('name', $name);
		$error=ProductModel::errorInProductName($name);
		if ($error!==null) {
			$this->setError ('name',$error);
		}
		/*
		The product controller should present the drop-down list and set the model's category to the one chosen by the user.
 
        */
		$catID=$this->getInput('catID');
		$this->setField('catID', $catID);
			//	echo $catID."hello world<br/>";//for test just to check html tag kb I thoug javascript forgont the post 
        //  $catID =(int)$catID;
		  		//var_dump($catID);
				echo $catID;
		$error=ProductModel::errorInProductCat($catID);
		if ($error!==null) {
			$this->setError ('catID',$error);

		}
		$description=$this->getInput('description');
		$this->setField('description', $description);
		$error = ProductModel::errorInProductDescription($description);
		if ($error!==null) {
			$this->setError ('description',$error);
		} 
		return null;
	}
	 private function getCategories($selectedID) {
		$db = $this->getDB();
		$sql ='select catID, name from categories order by name';
		$rowset= $db->query($sql);
		echo $rowset;
		$list = new ListSelectionView($rowset);
		$list = setFormName('categoryID');
		$list = setIdColumn('categogyID');
		return $list->getHtml();
	 
	 
	 
	 
	 }
	protected function updateModel($model) {
		$productName=$this->getField('name');//get infro from input boxes 
		$description=$this->getField('description');
		$productPrice=$this->getField('price');
		$catID=$this->getField('catID');// for test get html data which is a list of categories ex technology furniture ect  
		//$catID =(int)$catID; //categroy id is an integer kab transforms that options into integers
		//var_dump($catID);//what type we got
		$model->setProductName($productName);
		$model->setProductPrice($productPrice);
		$model->setDescription($description);
		$model->setCategoryId($catID);
		$model->save();
		$this->redirectTo('admin/products',"Product '$productName' has been saved");
	}
	protected function deleteModel($model) {
		$name=$model->getProductName();
		$model->delete();
		$this->redirectTo('admin/products',"Product '$name' has been deleted");
	}	
}
?>
