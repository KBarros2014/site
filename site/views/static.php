<?php
include 'lib/view.php';

class StaticView extends View {

	public function __construct($context) {
		parent::__construct($context);
	}

	public function prepare () {
		parent::prepare();
		$this->addContent($this->getModel()->getContent());
	}
}
?>