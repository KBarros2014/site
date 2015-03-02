<?php
/*

   
  e list controller with pagination
   ========================================
	
*/
include 'lib/paginator.php';
include 'lib/abstractController.php';
include 'lib/view.php';

abstract class ListController extends AbstractController {
	private $paginator;
	private $model;
	
	public function __construct(IContext $context) {
		parent::__construct($context);
		$this->paginator = new Paginator();
		$this->model=null;
	}
	
	protected function setModel(AbstractListModel $model) {
		$this->model=$model;
	}
	public function setRecordsPerPage($count) {
		$this->paginator->setRecordsPerPage($count);
	}
	public function setMaxPages($count) {
		$this->paginator->setMaxPages($count);
	}
	public function setLinkRoot($link) {
		$this->paginator->setLinkRoot($link);
	}
	
	// the real work happens in getView

	/*
	if the controlling class has a token, it should create a paginator
	   and then call the parseFromToken method. This will set the paginator 
	   data. The controlling class can call the getStartRecord() method to
	   determine the offset to use in database queries. The getHtml() method
	   will provide an appropriate paginator for display to the user.
	*/

	protected function getView($isPostback) {
		$uri=$this->getURI();
		$token = $uri->getPart();
	
		if ($token !=='') {
			if ($this->paginator->isValidToken ($token)){
				$this->paginator->parsefromToken($token); 
			} else {
				throw new InvalidRequestException('Invalid request URI token');
			}
		} else {
			$this->paginator->setRecordCount($this->getRecordCount());	
		}
		$limit = $this->paginator->getRecordsPerPage();
		$totalRecords = $this->paginator->getRecordCount();
		$offset=$this->paginator->getStartRecord();
	
		// get query, add limit clause if not all records will fit on page
		$sql=$this->getSelectionSQL();		
		if ($totalRecords > $limit) {
			$sql.=" limit $limit offset $offset";
		}
		$rows=$this->getDB()->query($sql);	
		if (count($rows)==0) {
			$html=$this->getNoRowsHtml();;
		} else {
			$html=$this->getHtmlForRows($rows);
			if ($totalRecords > $limit) {
				$html.='<br/>&nbsp;'.$this->paginator->getHtml();
			}	
		}		
		$html.=$this->getExtraHtml();	
		$view= new View($this->getContext());	
		$view->setModel(null);
		$view->setTemplate('html/masterPage.html');
		$view->setTemplateField('pagename',$this->getPageName());
		$view->addContent($html);
		return $view;
	}
	
	private function getRecordCount() {
		$db=$this->getDB();
		$sql=$this->getCountSQL();
		$rows=$db->query($sql);
		if ( count($rows) !== 1) {
			throw new LogicException ('Invalid count SQL: multiple rows returned');
		}
		$row = $rows[0];
		if ( count($row) !== 1) {
			throw new LogicException ('Invalid count SQL: multiple columns returned');
		}
		return array_values($row)[0];
	}
	protected abstract function getCountSQL();
	protected abstract function getSelectionSQL();
	protected abstract function getPageName();
	protected abstract function getNoRowsHtml();
	protected abstract function getHtmlForRows($rows);
	protected abstract function getExtraHtml();
}
?>
