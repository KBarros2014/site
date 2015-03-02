<?php

class SessionTest extends UnitTest {
	function __construct($context) {
		parent::__construct($context);
	}
		
	protected function doTests() {
		session_destroy();
		$session = new Session();

		$this->assertFalse ($session->isKeySet('a'),'The default for a key is unset');
		$session->set('a','value for a');
		$this->assert($session->isKeySet('a'),'The key should be set after a set call');
		$this->assertEqual ($session->get('a'),'value for a','Values should be remembered');
		$session->unsetKey('a');
		$this->assertFalse ($session->isKeySet('a'),'Unset should clear the key');

		$session->set('b','value for b');
		$this->assert($session->isKeySet('b'),'The key should be set after set call');
		$this->assertEqual ($session->get('b'),'value for b','Values should be remembered');		
		$session->changeContext();
		$this->assert($session->isKeySet('b'),'The key should be remembered after a context change');
		$this->assertEqual ($session->get('b'),'value for b','Values should be remembered after a context change');
		
		$session->clear();
		$this->assertFalse($session->isKeySet('b'),'key should be forgotten after a clear call');		
	}
}
?>
	