<?php
//include 'lib/abstractModel.php';

class TestModel extends AbstractModel {

	private $context;
	private $name;
	private $author;
	private $setupSQL;
	private $outcome;
	private $unitTest;
	private $asserts;
	private $failures;
	
	public function __construct($db,$context,$filename) {
		
		parent::__construct($db);
		$this->context=$context;
		$this->name=$filename;
		$this->author='unspecified';
		$this->outcome=null;
		$this->setupSQL=null;	
		$this->unitTest=null;
		$this->asserts=0;
		$this->failures=0;	
		$this->load($filename);
	}
	
	private function load($filename) {	
		$fileText=file_get_contents('tests/'.$filename);
		$testConfig=json_decode($fileText,true,10);
		if ($testConfig===null) {
			throw new ConfigurationException('Invalid test file format for '.$filename);
		}
		if (isset($testConfig['name'])) {
			$this->name=$testConfig['name'].' ('.$filename.')';
		}
		if (isset($testConfig['author'])) {
			$this->author=$testConfig['author'];
		}
		if (isset($testConfig['setupSQL'])) {
			$this->setupSQL=$testConfig['setupSQL'];
		}
		if (isset($testConfig['unitTest'])) {
			$this->unitTest=$testConfig['unitTest'];
		}
		if (isset($testConfig['SQLscript'])) {
			$this->loadSQL($testConfig['SQLscript']);
		}
	}
	
	public function getName() {
		return $this->name;
	}
	public function getAuthor() {
		return $this->author;
	}
	public function getOutcome() {
		return $this->outcome;
	}
	public function getAsserts() {
		return $this->asserts;
	}
	public function getFailures() {
		return $this->failures;
	}
	
	public function runTest() {
		try {
			if ($this->setupSQL !== null) {
				foreach ($this->setupSQL as $sql) {
					$sql=trim($sql);
					if (substr($sql,0,2)!=='--') {
						$this->getDB()->execute($sql);
						//echo $sql."<br/>\n";
					}
				}
			}
			if ($this->unitTest !== null) {
				$testClass = $this->unitTest;
				$testPath='unitTests/'.$testClass.'.php';	
				include $testPath;
				$unitTest= new $testClass($this->context);
				$this->outcome=$unitTest->getResults();
				$this->asserts=$unitTest->getAsserts();
				$this->failures=$unitTest->getFailures();
			}
		} catch (Exception $ex) {
			$this->outcome='There is an error in the set up of this test. The exception is: '.$ex->getMessage();
		}
	}
	
	private function loadSQL($filename) {	
		if (!$f=fopen($filename,"r") ){
			throw new ConfigurationException('Invalid test SQL file: '.$filename);
		}
		$sql=array();
		$statement='';
		while (!feof($f)) {
			$line=trim(fgets($f,4096));
			$last=substr($line,strlen($line)-1);
			if ($line=='' || substr($line,0,2)=='--') {
				continue;
			}
			$statement.=$line;		
			
			if ($last==';') {
				$sql[]=$statement;
	//			echo $statement.'<br/>';
				$statement='';
			} else {
				$statement.=' ';
				
			}
		}
		if ($statement!=='') {
			$sql[]=$statement;
		}
		fclose($f);
		if ($this->setupSQL==null) {
			$this->setupSQL=$sql;
		} else {
			foreach ($sql as $statement) {
				$this->setupSQL[]=$sql;
			}
		}
	}
}
?>