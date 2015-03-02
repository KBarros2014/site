<?php
include 'lib/view.php';

class TestsView extends view {

	public function __construct($context) {
		parent::__construct($context);
	}

	public function prepare () {
		$tests=$this->getModel()->getTests();
		$numTests=0;
		$numOK=0;
		$numAsserts=0;
		$numFailures=0;
		
		$content="<table>\n".
		         "<tr><th>Test Name</th><th>Author</th><th>Outcome</th><th>Asserts</th><th>Failures</th></tr>\n";
		foreach ($tests as $test) {
			$name=$test->getName();
			$author=$test->getAuthor();
			$outcome=$test->getOutcome();
			$asserts=$test->getAsserts();
			$failures=$test->getFailures();
			$numTests++;
			$numAsserts+=$asserts;
			$numFailures+=$failures;
			if ($outcome===null) {
				$outcome='OK';
				$numOK++;
			} else {
				$outcome='<span style="color:red;">'.$outcome.'</span>';
			}
			$content.="<tr><td>$name</td><td>$author</td><td>$outcome</td><td>$asserts</td><td>$failures</td></tr>\n";
		}
		$content.="</table>\n";
		$numErrors=$numTests-$numOK;
		$content.="<p>$numTests tests were run. ".
		           "There were $numErrors failed tests and $numOK tests were OK;".
				   " $numFailures of $numAsserts assertions 	failed.</p>";

		$this->addContent($content);
	}
}
?>