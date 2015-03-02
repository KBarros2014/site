<?php

class UnitTest {
	private $context;
	private $errors;
	private $asserts;
	private $failures;
	
	function __construct($context) {
		$this->context=$context;
		$this->errors=null;
		$this->asserts=0;
		$this->failures=0;
	}
	
	function getContext() {
		return $this->context;
	}
	
	function getResults() {
		$this->doTests();
		return $this->errors;
	}
	
	function getAsserts() {
		return $this->asserts;
	}

	function getFailures() {
		return $this->failures;
	}
	
	protected function doTests() {
		throw new Exception ('Unit test not implemented');
	}
	
	protected function setError($message) {
		if ($this->errors==null) {
			$this->errors=$message;
		} else {
			$this->errors.='<br/>'.$message;
		}
		$this->failures++;
	}
	protected function assert($test,$message) {
		$this->asserts++;
		if (!$test) {
			$this->setError ($message);
		}
	}
	
	protected function assertFalse($test,$message) {
		$this->asserts++;
		if ($test) {
			$this->setError ($message);
		}
	}
	protected function assertEqual($first, $second, $message) {
		$this->asserts++;
		if ($first!=$second) {
			$message.=", expecting '$second', found: '$first'";
			$this->setError ($message);
		}
	}
	
	protected function expectException ($exception, $function) {
		try {
			$this->asserts++;
			if (is_callable($function)) {
				call_user_func($function);
				$this->setError ('Test did not throw expected exception:'.$ex->getMessage());
			} else {
				$this->setError ('Supplied function is not callable');
			}
		} catch (Exception $ex) {
			$this->assertEqual(get_class($ex),get_class($exception),'Unexpected exception type raised');
			$this->asserts++;
			$this->assertEqual($ex->getMessage(),$exception->getMessage(),'Unexpected exception text');
		}
	}
}
?>
	