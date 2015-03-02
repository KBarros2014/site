<?php
/*


    A simple menu management class
    ==============================

	This will create a simple menu with a div containing an unordered list.
	List items will have an anchor with text and an href for the linked page. 
	Each item also has a key and one key may be specified as selected. The list
	item corresponding to the selected key will be given a CSS class of "selected".
	
	Usage:
		1) Create an instance of the class. Optionally call setMenuClass to give 
		   it a css class to use on the containing div; the default is "menu".
		   
		2) Call setMenuItem once for each menu item
		
		3) (Optionally) Call setSelected to mark the currently selected menu item.
		   
		4) Call the getHTML() method to return the Html needed. 
		
*/

class MenuBar {

	private $href;
	private $text;
	private $selected=null;
	private $menuClass;
	
	public function __construct() {
		$this->href=array();
		$this->text=array();	
		$this->menuClass='menu';
	}
	public function setMenuClass ($menuClass) {
		$this->menuClass=$menuClass;
	}
	public function setMenuItem ($key, $href, $text) {
		$this->href[$key]=$href;
		$this->text[$key]=$text;
	}
	public function setSelected($key) {
		$this->selected=$key;
	}
	public function getHtml() {
		$html='<div class="'.$this->menuClass.'"><ul>';
		foreach ($this->href as $key=>$href) {
			$html.='<li';
			if ($key==$this->selected) {
				$html.=' class="selected"';
			}
			$html.='><a href="'.$href.'" >'.$this->text[$key].'</a></li>';	
		}
		$html.='</ul></div>'.PHP_EOL;
		return $html;
	}
}
