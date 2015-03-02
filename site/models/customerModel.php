<?php
//kB
class CustomerModel extends  AbstractModel{

	private $customerId;
	private $customerFirstName=null;
	private $customerLastName=null;
	private $customerAddress = null;
	private $customerCity = null;
	private $customerPostCode =null;
	private $changed;

	public function __construct($db, $customerId=null) {
		parent::__construct($db);
		$this->customerId=$customerId;
		$this->changed = false;
		if ($customerId !== null) {
			$this->load($customerId);
		}
		
	}
	
	public function getCustomerId() {
		return $this->customerId;
	}

	public function getCustomerFirstName() {
		return $this->customerFirstName;
	}
	public function getCustomerLastName(){
		return $this->customerLastName;
	
	}
	public function getCustomerDetails(){
		return $this->getCustomerFirstName()." ".$this->getCustomerLastName();
	
	}
	public function setCustomerFirstName($value) {
		$error=$this->errorInCustomerFirstName($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->customerFirstName=$value;
		$this->changed=true;
	}
	public function setCustomerLastName($value) {
		$error=$this->errorInCustomerLastName($value);
		if ($error==null ){
			throw new InvalidDataException($error);
		}
		$this->customerLastName=$value;
		$this->changed=true;
	}
	public function getCustomerPostCode() {
		return $this->customerPostCode;
	}
	public function setCustomerPostCode($value) {
		$error=$this->errorInCustomerPostCode($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->customerPostCode=$value;
		$this->changed=true;
	}
	
	
	public function getCustomerAddress() {
		return $this->customerAddress;
	}
	
	public function setCustomerAddress($value) {
		$error=$this->errorInCustomerAddress($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->customerAddress=$value;
		$this->changed=true;
	}
	public function getCustomerCity() {
		return $this->customerCity;
	}
	public function setCustomerCity($value) {
		$error=$this->errorInCustomerCity($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->customerCity=$value;
		$this->changed=true;
	}

	public function hasChanges() {
		return $this->changed;
	}
	
	private function load($customerId) {
		$sql="select customerFirstName, customerLastName, customerAddress, customerEmail  from customers ".
			 "where customerId = $customerId";
		$rows=$this->getDB()->query($sql);
		if (count($rows)!==1) {
			throw new InvalidDataException("Customer  ($customerId) not found");
		}
		$row=$rows[0];
		$this->customerFirstName=$row['customerFirstName'];
		$this->customerLastName=$row['customerLastName'];
		$this->customerAddress=$row['customerAddress'];
		$this->customerEmail=$row['customerEmail'];
		$this->customerId=$customerId;
		$this->changed=false;
	}
	
	public function save() {//the save function is to be fixed
		$id=$this->customerId;
		if ($this->customerFirstName==null || $this->customerLastName==null|| $this->customerAddress==null || $this->customerEmail==null) {
			throw new InvalidDataException('Incomplete data');
		}
		$firstName=$this->customerFirstName;
		$lastName=$this->customerLastName;
		$address=$this->customerAddress;
		$city = $this->customerCity;
		$postCode = $this->customerPostCode;
		$email =$this->customerEmail;
		
		if ($this->id===null) {
			$sql="insert into customers (customerFirstName, customerLastName, customerAddress, customerCity, customerEmail, customerEmail) values (".
						"'$firstName', '$lastName','$$address', '$city','$postCode', '$email')";
			$this->getDB()->execute($sql);
			$this->id=getDB()->insertID();	
		} else {
			$sql="update customers ".
					"set customerFirstName='$firstName', ".
			            "customerLastName='$lastName' ".
						 "customerAddress='$address' ".
						  "customerCity='$city' ".
						   "customerPostCode='$postCode' ".
						"where customerId= $customerID";
			$this->getDB()->execute($sql);
		}
		$this->hasChanges=false;
	}
	
	public function delete () {
	    $sql="delete from customers where customerId = $id";
		$rows=$this->getDB()->execute($sql);
		$this->id=$null;
		$this->changed=false;
	}
	//// this functions below should ensure fields are entered kBarrs and I don t fix them now
	//a get email address function should be implemented which s pretty stratig foward in customer controller
	public static function errorInCustomerAddress($value) {
		if ($value==null || strlen($value)==0) {
			return 'Address must be specified';
		}
		if (strlen($value)>30) {
			return 'error customer first Name name must have no more than 30 characters';
		}
		return null;
	}
	public static function errorInCustomerEmail($value) { //some checks to be implemented here kb
		if ($value==null || strlen($value)==0) {
			return 'Email must be specified';
		}
		if (strlen($value)>30) {
			return 'error customer first Name name must have no more than 30 characters';
		}
		return null;
	}
	public static function errorInCustomerCity($value) {//some checks to be implemented here kb
		if ($value==null || strlen($value)==0) {
			return 'City must be specified';
		}
		if (strlen($value)>30) {
			return 'custmoer city first Name name must have no more than 30 characters';
		}
		return null;
	}
	
	public static function errorInCustomerFirstName($value) {//some checks to be implemented here kb
		if ($value==null || strlen($value)==0) {
			return 'Product name must be specified';
		}
		if (strlen($value)>30) {
			return 'error customer first Name name must have no more than 30 characters';
		}
		return null;
	}
	public static function errorInCustomerPostCode($value) {
	
		if ($value==null || strlen($value)==0) {
			return 'code must be specified';
		}
		if (strlen($value)>30) {
			return 'error customer first Name name must have no more than 30 characters';
		}
		return null;
	}
	
	public static function errorInCustomerLastName($value) {
		if ($value==null || strlen($value)==0) {
			return 'Customer last name must be specified';
		}
		if (strlen($value)>30) {
			return 'error In CusomerLastname must have no more than 30 characters';
		}
		return null;
	}
	}
?>
