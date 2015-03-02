<?php
/*
   
   
   Sample CRUD controller for entity editing
   =========================================

   The following URI patterns are handled by this controller: 
   
   /something/new         	create a new entity
   /something/edit/nn		edit entity nn
   /something/delete/nn	    delete entity nn
   /something/view/nn       view entity nn
   
   (nn is the entity ID)
*/

include 'lib/abstractController.php';
include 'lib/abstractModel.php';
include 'lib/view.php';

abstract class crudController extends AbstractController {

	private $fields;
	private $errors;
	private $subViewTemplate;
	private $hasErrors;
	
	public function __construct(IContext $context) {
		parent::__construct($context);
		$fields=array();
		$errors=array();
		$this->hasErrors=false;
	}

	protected function setField($field, $value='') {
		$this->fields[$field]=$value;
		$this->errors[$field]='';
	}	

	protected function getField($field) {
		return $this->fields[$field];
	}	

	protected function setError ($field, $error) {
		$this->errors[$field]=$error;
		$this->hasErrors=true;
	}	

	protected function getView($isPostback) {
		$uri=$this->getURI();
		$action=$uri->getPart();
		switch ($action) {
			case 'new':
				$this->subviewTemplate = $this->getTemplateForNew();
				return $this->handleEdit($isPostback);
			case 'edit':
				$this->subviewTemplate = $this->getTemplateForEdit();
				return $this->handleEdit($isPostback,$uri->getID());	
			case 'view':
				$this->subviewTemplate = $this->getTemplateForView();
				return $this->handleView($isPostback,$uri->getID());	
			case 'delete':
				$this->subviewTemplate = $this->getTemplateForDelete();
				return $this->handleDelete($isPostback,$uri->getID());	
			default:
				throw new InvalidRequestException ("Invalid action in URI");
		}	
	}

	private function handleEdit($postback, $id=null) {
	
		if (!$postback) {
			$model=$this->createModel($id); // subclass will create 
			$this->getModelData($model);	// subclass will call setfield for each field
			return $this->createView($id); 			// form with data from DB (or blank if ID null)		
		} else {
			// get user data
			$this->getFormData();			// subclass will call setfield and seterror
			if ($this->hasErrors) {
				return $this->createView($id); 		// form with user data and errors
			} else {
				$model=$this->createModel($id);  // subclass will create
				$this->updateModel($model);		 // subclass will update and redirect
				return null;
			}
		}
	}
	
	private function handleView($postback, $id) {
		if ($id===null) {
			throw new InvalidRequestException ("Invalid ID in URI");
		}
		$model=$this->createModel($id); // subclass will create 
		$this->getModelData($model);	// subclass will call setfield for each field
		return $this->createView($id); 			// form with data from DB (error if ID null)		
	}
	
	private function handleDelete($postback, $id) {
		if ($id===null) {
			throw new InvalidRequestException ("Invalid ID in URI");
		}
		$model = $this->createModel ($id); // subclass will create
		if (!$postback) {
			// display form with user data
			$this->getModelData($model);	// subclass will call setfield for each field
			return $this->createView($id);
		} else {
			$this->deleteModel($model);  // confirmation postback
		}
	}

	private function createView($id) {
		$view=new View($this->getContext());
		$view->setTemplate('html/masterPage.html');
		$view->setTemplateField('pagename',$this->getPagename());
		$view->setSubviewTemplate($this->subviewTemplate);		
		$view->setSubviewField ('id',$id);
		foreach ($this->fields as $field=>$value) {
			$view->setSubviewField ($field,$value);
			$view->setSubviewField ($field.'Error',$this->errors[$field]);
		}
		return $view;
	}
	
	// the following methods must all be overridden in subclasses
	abstract protected function getPagename();
	abstract protected function getTemplateForNew();
	abstract protected function getTemplateForEdit(); 
	abstract protected function getTemplateForDelete();
	abstract protected function getTemplateForView();
	abstract protected function createModel($id);
	abstract protected function getModelData($model);
	abstract protected function getFormData();
	abstract protected function updateModel($model);
	abstract protected function deleteModel($model);
}
?>
