<?php

class UriTest extends UnitTest {
	function __construct($context) {
		parent::__construct($context);
	}
		
	protected function doTests() {
		$site='mysite.com/';
		$path='a/b/c/d/5';
		$uri=new URI ($site, $path);
		$this->assertEqual ($uri->getSite(),  'mysite.com/','site');
		$this->assertEqual ($uri->getSite(),  'mysite.com/','site');
		$this->assertEqual ($uri->getRawURI(),'mysite.com/a/b/c/d/5','Test URI');
		$this->assertEqual ($uri->getPart(),  'a','First part of URI /a/b/c/d/5');
		$this->assertEqual ($uri->getRemainingParts(),'b/c/d/5','Test URI remaining');
		$this->assertEqual ($uri->getPart(),  'b','Second part of URI /a/b/c/d/5');
		$this->assertEqual ($uri->getRemainingParts(),'c/d/5','Test URI remaining');
		$this->assertEqual ($uri->getPart(),  'c','Third part of URI /a/b/c/d/5');	
		$this->assertEqual ($uri->getRemainingParts(),'d/5','Test URI remaining');
		$this->assertEqual ($uri->getPart(),  'd','Fourth part of URI /a/b/c/d/5');	
		$this->assertEqual ($uri->getRemainingParts(),'5','Test URI remaining');
		$this->assertEqual ($uri->getID(),    5,  'Fifth part of URI /a/b/c/d/5');
		$this->assertEqual ($uri->getRemainingParts(),'','Test URI remaining');
		$this->assertEqual ($uri->getRawURI(),'mysite.com/','URI after stripping off parts');
		$uri->prependPart('x');
		$uri->prependPart('y');
		$this->assertEqual ($uri->getRawURI(),'mysite.com/y/x','URI after prepending parts');	
	}
}
?>
	