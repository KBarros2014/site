<?php
/*

   
   A simple drop-down list
   =======================

   Usage:
   
	1) query the database to get a set of rows that includes an id column
	   and a value to display to the user.
	2) create a list from this row-set
		$list=new ListSelectionView($rowset)
	3) set the name to be used in the form
		$list->setFormName($name);
	4) set the name of the id column in the row-set
		$list->setIdColumn($name);
	5) set the name of the value column in the row-set (optional - default is ID)
		$list->setValueColumn($name);
	6) set the id value currently selected (optional - default is no selection)
		$list->setValueColumn($name);
	7) get the html for the form
		$html=$list->getHtml();
		
	
*/
class ListSelectionView {

	private $data;
	private $formName;
	private $idColumn;
	private $valueColumn;
	private $selected;
	
	
	
	public function __construct($rowset) {
		$this->data=$rowset;
		$this->formName=null;
		$this->idColumn=null;
		$this->valueColumn=null;
		$this->selected=null;
	}
	public function setFormName($name) {
		$this->formName=$name;
	}
	public function  setIdColumn ($column) {
		$this->idColumn=$column;
	}
	public function  setValueColumn ($column) {
		$this->valueColumn=$column;
	}	
	public function  setSelectedId ($selected) {
		$this->selected = $selected;
	}
	
	public function getHtml(){
		if ($this->idColumn==null) {
			throw new LogicException ('ID column must be specified');
		}
		if ($this->valueColumn==null) {
			$this->valueColumn=$this->idColumn;
		}
		
		$html='<select name="'.$this->formName.'">'.PHP_EOL;
		foreach ($this->data as $row) {
			$id=$row[$this->idColumn];
			$value=$row[$this->valueColumn];
			$html.='<option value="';
			$html.=$id.'"';
			if ($id == $this->selected) {
				$html.=' selected="selected"';
			}	
			$html.=">$value</option>\n";
		}
		$html.="</select>\n";
		return $html;
	}
}
?>
