<?php
include 'lib/menuBar.php';

class View {
	private $context;
	private $model;
	private $menuBar;
	private $template;
	private $fields;
	private $content;
	private $subviewTemplate;
	private $subviewFields;

	public function __construct($context) {
		$this->context=$context;
		$this->model=null;
		$this->template=null;
		$this->fields=array();
		$this->menuBar=new Menubar();
		$this->content="";
		$this->subviewTemplate=null;
		$this->subviewFields=array();
	}
	
	public function getContext() {
		return $this->context;
	}
	
	public function getModel() {
		return $this->model;
	}
	
	public function setModel($model) {
		$this->model=$model;
	}
	public function getMenuBar() {
		return $this->menuBar;
	}
	public function setTemplate($template) {
		$this->template=$template;
	}
	public function setTemplateField($name,$value){
		$this->fields[$name]=$value;
	}
	public function setTemplateFields($fields) {
		foreach ($fields as $name=>$value) {
			$this->setTemplateField($name, $value);
		}
	}
	public function setSubviewTemplate($template) {
		$this->subviewTemplate=$template;
	}
	
	public function setSubviewField($field,$value) {
		$this->subviewFields[$field]=$value;
	}

	public function addContent($value) {
		$this->content.=$value;
	}
	public function render() {
		$html=file_get_contents($this->template);
		$html=str_replace('##menubar##', $this->menuBar->getHtml(),$html);
		$html=str_replace('##content##', $this->content,$html);
		foreach ($this->fields as $name=>$value) {
			$key='##'.$name.'##';
			$html=str_replace($key, $value,$html);
		}	
		print $html;
	}
	//	expect subclass to override
	public function prepare () {

		$user=$this->context->getUser();
		$menu=$this->getMenuBar();
		if ($user->isAdmin()) {
			$menu->setMenuItem('a', '##site##logout', 'Log out') ;
			$menu->setMenuItem('b', '##site##admin/categories', 'Categories') ;
			$menu->setMenuItem('c', '##site##admin/products', 'Products') ;
			$menu->setMenuItem('d', '##site##admin/customers', 'Customers') ;

			$menu->setMenuItem('d', '##site##admin/orders', 'Orders') ;

			$menu->setMenuItem('e', '##site##admin/orders', 'Unsent orders') ;
			$menu->setMenuItem('f', '##site##checkout', 'Check out') ;

		} else {
			$menu->setMenuItem('a', '##site##', 'Home') ;
			$menu->setMenuItem('b', '##site##login', 'Log in') ;	
			$menu->setMenuItem('c', '##site##checkout', 'Check out') ;
			$menu->setMenuItem('d', '##site##shoppingCart', 'Cart') ;					
		}
	
		if ($this->subviewTemplate!==null) {
			// Read subview	
			$html=file_get_contents($this->subviewTemplate);
			foreach ($this->subviewFields as $name=>$value) {
				$key='##'.$name.'##';
				$html=str_replace($key, $value,$html);
			}	
			$this->addContent($html);
		}
	}
}
?>



