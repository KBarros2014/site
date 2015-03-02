<?php
//14/10/2014 kb
include 'controllers/crudController.php';
include 'models/customerModel.php';

class CustomerController extends CrudController {

	public function __construct(IContext $context) {
		parent::__construct($context);
	}

	protected function getPagename(){
		return 'Customers';
	}
	
	// the following methods are the must-overrides in the Crud controller
	protected function getTemplateForNew () {
		return 'html/forms/adminCustomersNew.html';
	}
	protected function getTemplateForEdit () {
		return 'html/forms/adminCustomersEdit.html';
	}
	protected function getTemplateForDelete () {
		return 'html/forms/adminCustomersDelete.html';
	}
	protected function getTemplateForView () {
		return 'html/forms/adminCustomersView.html';
	}
	protected function createModel($id) {
		return new CustomerModel($this->getDB(),$id);
	}
	protected function getModelData($model) {
		$this->setField('firstname', $model->getCustomerFirstName());
		$this->setField('lastName',$model->getCustomerLastName());
		$this->setField('address',$model->getCustomerAddress());//kb
		$this->setField('city',$model->getCustomerCity());
		$this->setField('postcode',$model->getCustomerPostCode());
		//$this->setField('email',$model->getcustomerEmail());for testing purposes I commented it out kBaros
		//I need to create the function in customer model
			
	}
	protected function getFormData() {
		$firstname=$this->getInput('firstname');
		$this->setField('firstname', $firstname);
		$error=CustomerModel::errorInCustomerFirstName($firstname);//function in custmoer modelkb
		if ($error!==null) {
			$this->setError ('firstname',$error);
		}
		$lastname=$this->getInput('lastname');
		$this->setField('lastname', $lastname);
		$error=CustomerModel::errorInCustomerLastName($lastname);//kb 
		
		if ($error!==null) {
			$this->setError ('firstname',$error);
			echo "dldld";
		}
		
		$address=$this->getInput('address');
		$this->setField('address', $address);//kb
		$error=CustomerModel::errorInCustomerAddress($address);
		if ($error!==null) {
			$this->setError ('address',$error);
		}
		$city=$this->getInput('city');
		$this->setField('city', $city);
		$error=CustomerModel::errorInCustomerCity($city);
		if ($error!==null) {
			$this->setError ('city',$error);
		}
		$postcode=$this->getInput('postcode');//kb
		$this->setField('postcode', $postcode);
		$error=CustomerModel::errorInCustomerPostCode($postcode);
		if ($error!==null) {
			$this->setError ('postcode',$error);
		}
		$email=$this->getInput('email');
		$this->setField('email', $email);
		$error=CustomerModel::errorInCustomerEmail($email);
		if ($error!==null) {
			$this->setError ('email',$error);
		}
		
	}
	protected function updateModel($model) {//this function gets input boxes asks model if it s right asves it kab
		$firstname=$this->getField('firstname');//kb
		$lastname=$this->getField('lastname');
		$city= $this->getField('city');
		$address=$this->getField('address');
		$postcode=$this->getField('postcode');
		$email=$this->getField('email');//please create a function for getting email kb
		$model->setCustomerFirstName($firstname);//could simplify wiht one function only kb
		$model->setCustomerLastName($lastname);	
		$model->setCustomerCity($city);
		//$model->setCustomerEmail($email);//to be implemented kb
		$model->setCustomerPostCode($postcode);
		$model->save();
		$this->redirectTo('admin/customers',"Customer '$firstname' has been saved");//I will change that kb
	}
	protected function deleteModel($model) {
		$name=$model->getName();//this does not work yet kb
		$model->delete();
		$this->redirectTo('admin/customers',"Customer '$name' has been deleted");
	}	
}
?>