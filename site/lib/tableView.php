<?php
/*
	Basic use:
		1) Create an instance from a result set ...
					$table=new HtmlTable($data);
		2) Define columns with setColumn
			a) (Simplest) just define a field name; This will also be used for the heading
					$table->setColumn('name');
			b)  Simple) Define a field name and a column heading.
					$table->setColumn('name','Customer Name');
			c)  (Complex) Define a field name, a column heading and a template.
					$table->setColumn('name','Customer Name','template');
		3) Retrieve html with
					$table->getHTML();
										   
	Templates comprise text with one or more embedded field codes. Each field
	code looks like <<field>> or <<field|format>>. Field refers to a database
	field in the row set. Format can be one of the following:
		currency    $xxx.xx
		integer     xxx
		decimal     xxx.xx
		date        d-MMM-YYYY
		sdate       d-MMM

*/
class TableView{

	private $resultSet;
	private $title;
	private $headings;
	private $templates;

	function __construct ( $resultSet )           {
		$this->resultSet=$resultSet;
		$this->title=null;
		$this->headings=array();
		$this->templates=array();
			}	   
	public function setColumn($fieldName, $heading=null, $template=null) {
		if ($heading == null) {
			$heading=$fieldName;
		}
		if ($template == null) {
			$template='<<'.$fieldName.'>>';
		}
		$this->headings[$fieldName]=$heading;
		$this->templates[$fieldName]=$template;
	}
	public function setTitle ($theTitle) {
		$this->title = $theTitle;
	}
	// Create an html table from the result set
	public function getHTML() {
		$html='<table';
		if ($this->title!==null){
			$html.= ' title="'.$this->title.'"';
		}             		
		$html.='>'.PHP_EOL;
	   
		// Add a header row with the headings
		$html .= "<tr>";
		foreach ($this->headings as $field=>$value) {
			$html .="<th class=\"$field\">$value</th>";
		}
		$html .= '</tr>'.PHP_EOL;;

		// add a detail row for each row in the recordset
		$rowClass="odd";  // odd if odd numbered row, else even
		foreach ($this->resultSet as $row){
			$html .='<tr class="'.$rowClass.'">';        
			foreach ($this->templates as $field=>$template) {
				$html .='<td class="'.$field.'">';
				$html .=$this->applyTemplate($row, $field, $template);              
				$html .='</td>';                                                           
			}
			$html .= "</tr>".PHP_EOL;
			$rowClass =($rowClass == 'odd') ? 'even' : 'odd';                                               
		}
		return $html.'</table>'.PHP_EOL ;
	}
   
	/*
	   This is a bit complex - take your time reading it.
	   The basic idea is that the template will look like this
	   "some text <<field1>> and some more text then <<field2>> etc"
	   We'll identify the fields <<fieldname>> and replace them by the
	   value of fieldname in the database row.
	*/
   
	// make a table cell entry from the template and the database row
	private function applyTemplate ($row, $field, $template) {
		$start=strpos($template,'<<');   // get first << (if any)
		if ($start===false) {
			return $template;
		}
		$work=$template;
		$class='tableCell';
		while ($start!==false) {  // note use of not identical (!==)
			$start+=2;                         // move to first character after <<
			$endAt=strpos($work,'>>',$start);  // get first >> after start of field
			if ($endAt===false) {
				$start=false;                       // there isn't one - format error
				return $work;
			} else {
				$size=$endAt-$start;  // how many characters in the fieldname?
				$source=substr($work,$start,$size); // get the field name
				if (strpos($source,'|')==false) {
					$dbValue = $row[$source];
				} else {
					$parse = explode('|',$source); // parse $source
					$field=$parse[0];             // first part is db field    
					$dbValue=$row[$field]; //get value from db row to substitute
					if (sizeof($parse)> 1) {
						$extra=$parse[1];            // second part is format
						$dbValue=$this->getFormattedField($dbValue, $extra);
					}
				}
				// replace placeholder field code with value from DB
				$work = str_replace('<<' . $source . '>>' ,$dbValue,$work);
			}
			$start+=strlen($dbValue);
			if ($start<strlen($work)) {
				$start=strpos($work,'<<',$start);         // look for next field	
			} else {
				$start=false;
			}
		}	// and repeat until all substitutions are made
		return $work;
	}
   
	//  Format $field as per $format specification
	private function getFormattedField ($field, $format) {
		switch ($format) {
			case 'currency' :
				return '$'.number_format ($field,2);
			case 'integer'  :
				return number_format ($field);
			case 'decimal'  :
				return number_format ($field,2);           
			case 'date'     :
				return date('d-M-Y',strtotime($field));
			case 'sdate'     :
				return date('d-M',strtotime($field));
			default:
				return $field;
		}
	}             
}
?>