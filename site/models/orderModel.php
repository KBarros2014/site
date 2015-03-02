<?php
//Alex
include 'models/ShoppingCart.php';
include 'lib/abstractModel.php';

class orderModel extends  AbstractModel{

	private $orderId;
	private $orderDate;
	private $ticketNo;
	private $orderAddress;
	private $sendDate;
	private $changed;
	private $intDate;
	
	public function __construct($db, $orderId=null) {
		parent::__construct($db);
		$this->init();
		if ($orderId !== null) {
			$this->load($orderId);
		}	
	}
	
		private function init() {
		$this->orderId=null;
		$this->orderDate=null;
		$this->ticketNo=null;
		$this->orderAddress=null;
		$this->sendDate=null;
		$this->changed=false;		
	}
	
	/***************************************
 * formats the date passed into format required by 'datetime' attribute of <date> tag
 * if no intDate supplied, uses current date.
 * @param intDate integer optional
 * @return string
 ******************************************/
 
	function getDateTimeValue( $intDate = null ) {
    $strFormat = 'Y-m-d H:i:s';
    $strDate = $intDate ? date( $strFormat, $intDate ) : date( $strFormat ) ; 
    return $strDate;
}
	
	public function getOrderId() {
		return $this->orderId;
	}

	public function getOrderDate() {
		return $this->getOrderDate;
	}
	
	public function setorderId($value) {
		$this->orderId=$value;
		$this->changed=true;
	}
	public function setorderDate($value) {
		$this->orderDate=$value;
		$this->changed=true;
	}

	public function setorderAddress($value) {
		$this->orderAddress=$value;
		$this->changed=true;
	}

	public function hasChanges() {
		return $this->changed;
	}
	
	private function load($orderId) {
		$sql="select orderId,orderDate,sendDate,customerId,TicketNo from orders where orderId = $orderId";
		$rows=$this->getDB()->query($sql);
		if (count($rows)!==1) {
			throw new InvalidDataException("order  ($orderId) not found");
		}
		$row=$rows[0];
		$this->orderId=$row['orderId'];
		$this->orderDate=$row['orderDate'];
		$this->sendDate=$row['sendDate'];
		$this->customerId=$row['customerId'];
		$this->TicketNo=$row['TicketNo'];
		$this->changed=false;
	}
	
	static function createFromCart ($db,ShoppingCart $cart) {
		try {
			$db->beginTransaction();
			$orderDate=date("Y-m-d h:i:s",time());
			$sendDate=null;
			$ticketNo=null;
			$customerID=$cart->getCustomerId();
			
			$sql="insert into orders (orderDate,sendDate,customerId,TicketNo) ".
				 "values ('$orderDate','$sendDate','$customerID','$ticketNo')";
			$db->execute ($sql);
			$orderID=$db->getInsertID();				
		
		//	var_dump ($cart);
			
			$count = $cart->getCount();
			for ($i=0 ; $i < $count ; $i++) {
				$item = $cart->getItemAt($i);
				$productID = $item->getItemCode();
				$quantity = $item->getQuantity();
				$sql="insert into OrderProducts(quantity, orderID, productID) ".
				"values ($quantity, $orderID, $productID)";
				$db->execute ($sql);
			}
			$db->commitTransaction();
		} catch (Exception $ex) {
			$db->rollbackTransaction();;
		}
	}
}
?>
