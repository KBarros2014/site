<?php
include 'lib/abstractModel.php';
include 'models/test.php';

class TestsModel extends AbstractModel {

	private $context;
	private $tests;
	
	public function __construct($db, $context) {
		parent::__construct($db);
		$this->context=$context;
		$this->tests=array();
		$this->load();
	}
	
	private function load() {
		// read file names in directory 'tests' into array
		$dir = "tests/";
		$filenames=array();
		
		// Sanity check
		if (!is_dir($dir)){
			throw new Exception ("Invalid 'tests' directory");
		}
		// Read directory contents
		if ($dh = opendir($dir)){
			while (($file = readdir($dh)) !== false){			
				if (!(substr($file,0,1)=='.')) {
					$filenames[]=$file;
				}
			}
			closedir($dh);
		}	
		// create a test for each filename 
		sort($filenames);
		foreach ($filenames as $filename) {
			$context=$this->context;
			$db=$context->getDB();
			$test=new TestModel ($db,$context,$filename);
			$this->tests[]=$test;
		}
	}
	
	public function getTests() {
		return $this->tests;
	}
	
	public function runTests() {
		foreach ($this->tests as $test) {
			$test->runTest();
		}
	}
}
?>