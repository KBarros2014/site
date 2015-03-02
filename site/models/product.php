<?php
//kB
include_once 'models/category.php';
class ProductModel extends AbstractModel {

	private $productId;
	private $productName=null;
	private $productDescription= null;
	private $productPrice=null;
	private $productPic=null;
	private $changed;
	private $category;
	private $catID = null;
	/*
	
	To create a new product ...
		$p = new Product ($db);
		... call setters
		... $p->save();
		
	To update a product ...
		$p = new Product ($id);
		... call setters
		... $p->save();
		
	*/
	
	
	public function __construct($db, $productId=null)  { //another field to be inserted here I will work on it after holiday
		parent::__construct($db);
		$this->productId=$productId;
		//$this->setProductName= ($productName);
		//$this->setProductPrice($productPrice);
		$this->changed = false;
		if ($productId !== null) {
			$this->load ($productId);
		}
		
	}
	
	public function getProductId() {
		return $this->productId;
	}
	

	public function getProductName() {
		return $this->productName;
	}
	public function setCategoryId($value){
	$error=$this->errorInProductCat($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
	    $this->catID =$value;
		$this->changed=true;
	}
	public function getCategoryId () {
		return $this->catID;
	
	
	}
	
	
	
	
	
	public function getCategory() {
		if ($this->category == null) {
			$this->category = new CategoryModel ($this->getDB(), $this->catID);
	}
	return $this->category;
	}
	public function setProductName($value) {
		$error=$this->errorInProductName($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->productName=$value;
		$this->changed=true;
	}
	
	public function getProductPrice() {
		return $this->productPrice;
	}
	
	public function setProductPrice($value) {
		$error=$this->errorInProductPrice($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->productPrice=$value;
		$this->changed=true;
	}
	
	public function getProductPic() {
		return $this->productPic;
	}
	
	public function setProductPic($value) {
		$error=$this->errorInProductPic($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->prodPic=$value;
		$this->changed=true;
	}
	
	public function hasChanges() {
		return $this->changed;
	}
	  
	private function load($productId) {
	if (!is_int($productId) && !ctype_digit($productId)) {
			throw new InvalidDataException("Invalid product ID ($productId)");
		}
		$sql="select productName, productDescription, productPrice, productPic from products ".
			 "where productID = $productId";
		$rows=$this->getDB()->query($sql);
		//echo $rows;
		
		if (count($rows)!==1) {
			throw new InvalidDataException("Product ID $productId not found");
		}
		
		$row=$rows[0];
		$this->productName=$row['productName'];
		$this->productDescription=$row['productDescription'];
		$this->producPrice= $row['productPrice'];  
		$this->productPic=$row['productPic'];//we do not have picutre 
		$this->productId=$productId;
		$this->changed=false;
	}
	
	public function save() {//save function to be perfected here // to be added more conditions on the contruct
		if ($this->changed) {
		              if ($this->productName==null || $this->productPrice==null || $this->productDescription==null ||$this->catID==null) {
				throw new InvalidDataException("Incomplete data Hi it s me testing again make sure you select cat");
		}
		echo $this->productName." ".$this->productPrice;// just for checking where it breaks kb
	    $db=$this->getDB();
		
		$productId=$this->productId;
		$productName=$this->productName;
		$productDescription=$this->productDescription;
		$productPic =$this->productPic;
		$productPrice= $this->productPrice;//lets see if breaks the code
		$catID = $this->catID;
			if ($productId === null) {
				$sql="insert into products(productName, productDescription, productPrice, productPic,catID) values (".
						"'$productName', '$productDescription', '$productPrice','$productPic','$catID')" ;
			echo $sql;
		$affected=$db->execute($sql);
		 echo $affected;
			if ($affected !== 1) {
					throw new InvalidDataException("Insert product failed");	
				}
			$this->productId=$db->getInsertID();
		} else {
			$sql="update products ".
					"set productName='$productName', ".
			            "productDescription='$productDescription' ".
						 "productPrice ='$productPrice' ".
						 	 "productPic ='$productPic' ".
					"where productID= $productId";
					if ($db->execute($sql) !== 1) {
					throw new InvalidDataException("Update product failed");	
				}
			
		}
		$this->hasChanges=false;
		//$this->changed =false;
		
	}
	}
		
		
	
	public function delete () {
	    $sql='delete from products where productID = '.$this->productId;;
		$rows=$this->getDB()->execute($sql);
		$this->id=$null;
		$this->changed=false;
	}

	public static function errorInProductName($value) {
		if ($value==null || strlen($value)==0) {
			return 'Product name must be specified';
		}
		if (strlen($value)>30) {
			return 'errorInProductName name must have no more than 30 characters';
		}
		return null;
	}
	
	public static function errorInProductPrice($value) {
		if ($value== null) {
			return 'Price must be specified';
		}
		if ($value <0) {
		return "No negative number no words please";
		}
		return null;
		}
	
	public static function errorInProductPic($value) {
		if ($value==null ) {// in the future we might need to check the extension of images 
			return 'Picture  must be supplied';
		}
		return null;
		}
	
	public function setDescription($value) { //needed  function
		$error=$this->errorInProductDescription($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->productDescription=$value;
		$this->changed=true;
	}
	
	public function getProductDescription() {
		return $this->productDescription;
	}
	
	public static function errorInProductCat($value) { 
		if ($value==null) {
			return 'Category  must be supplied';
		}
		return null;
		}
	/*	public static function isExistingId($db,$id) {
		if ($id==null){
			return false;
		}
		if (!is_int($id) && !(ctype_digit($id))) {
			return false;
		}
		*/
	public static function errorInProductDescription($value) {//irrelevant  kb
		if ($value==null || strlen($value)==0) {
			return 'desc name must be specified';
		}
	
      if ($value <0){
	  return "not negative number";
	  }
		
		
		return null;
	}
}
?>
