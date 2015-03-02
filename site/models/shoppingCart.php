
<?php 

class ShoppingCart {
	private $context;
	private $cart;
	private $customerId;
	
	function __construct (IContext $context) {
		$this->context=$context;
		if ($context->getSession()->isKeySet('cart')) {
			$this->cart=$context->getSession()->get('cart');
		} else {
			$this->cart=array();
		}
	}
	public function setCustomerId($cust) { //kb
		$this->customerId = $cust;
	
	
	}
	public function getCustomerId() { //kb
		return $this->customerId;
	}

	private function  save() {
		$this->context->getSession()->set('cart',$this->cart);
	}
	public function delete() {
		$this->context->getSession()->unsetKey('cart');
		$this->cart=array();
	}
	public function addItem(ShoppingCartItem $item) {
		$this->cart[]=$item;
		$this->save();
	}
	public function removeItemAt($index) {
		unset($this->cart[$index]);
		$this->cart = array_values($this->cart);
		$this->save();
	}
	public function getCount() {
		return count($this->cart);
	}
	public function getItemAt($index) {
		return $this->cart[$index];
	}
	public function getTotalPrice() {
		$total=0;
		foreach ($this->cart as $item) {
			$total+= $item->getTotal();
		}
		return $total;
	}
	public function getTotalQuantity() {
		$total=0;
		foreach ($this->cart as $item) {
			$total+= $item->getQuantity();
		}
		return $total;
	}
}

class ShoppingCartItem {
	private $itemCode;
	private $quantity;
	private $price;
	
	public function __construct($itemCode, $quantity, $price) {
		$this->itemCode =$itemCode;
		$this->quantity =$quantity;
		$this->price =$price;	
	}
	public function getItemCode() {
		return $this->itemCode;
	}
	public function getQuantity() {
		return $this->quantity;
	}
	public function getPrice() {
		return $this->price;
	}
	public function getTotal() {
		return $this->quantity * $this->price;
	}
}

?>
