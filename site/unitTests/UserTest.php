<?php

class UserTest extends UnitTest {
	private $usr; //for exception tests
	
	function __construct($context) {
		parent::__construct($context);
	}

	protected function doTests() {
		$context=$this->getContext();
		$timestamp=time();		
		$user=$context->getUser();
		
		// check default is anonymous user
		$user=$context->getUser();
		$this->assertEqual($user->getUserID(),null,"Default userid should be null (anonymous user)");
		$this->assertEqual($user->getName(),"","Default username should be blank (anonymous user)");
		$this->assertEqual($user->getEmail(),null,"Default email should be blank (anonymous user)");
		$this->assertFalse($user->isMember(),"Anonymous user should not have membership rights");
		$this->assertFalse($user->isAdmin(),"Anonymous user should not have administrator rights");
		$this->assert(abs($user->getDateCreated()-$timestamp)<2,"Date created should be around now");
		$this->assert(abs($user->getLastLogin()-$timestamp)<2,"Last login should be around now");
		
		// check login as Mike Lopez (administrator)
		$user->login("mike.lopez@cpit.ac.nz","cabbages");
		$this->assertEqual($user->getUserID(),1,"Mike Lopez userid should be 1");
		$this->assertEqual($user->getName(),"Mike Lopez","Username should be Mike Lopez");
		$this->assertEqual($user->getEmail(),'mike.lopez@cpit.ac.nz',"Email should be mike.lopez@cpit.ac.nz");
		$this->assert($user->isMember(),"Mike Lopez should have membership rights");
		$this->assert($user->isAdmin(),"Mike Lopez  should have administrator rights");
		$this->assert($timestamp-$user->getDateCreated()>25*24*3600,"Date created should be at least 25 days ago");
		$this->assert(abs($user->getLastLogin()-$timestamp)<2,"Last login should be around now");
	
		// check login as Mike Lance (member)
		$user->login("lancem@cpit.ac.nz","peanuts");
		$this->assertEqual($user->getUserID(),2,"Mike Lance userid should be 2");
		$this->assertEqual($user->getName(),"Mike Lance","Username should be Mike Lance");
		$this->assertEqual($user->getEmail(),'lancem@cpit.ac.nz',"Email should be lancem@cpit.ac.nz");
		$this->assert($user->isMember(),"Mike Lance should have membership rights");
		$this->assertFalse($user->isAdmin(),"Mike Lance should not have administrator rights");
		$this->assert($timestamp-$user->getDateCreated()>25*24*3600,"Date created should be at least 25 days ago");
		$this->assert(abs($user->getLastLogin()-$timestamp)<2,"Last login should be around now");
	
		// Make sure logout is OK
		$user->logout();
		$this->assertEqual($user->getUserID(),null,"Default userid should be null (anonymous user)");
		$this->assertEqual($user->getName(),"","Default username should be blank (anonymous user)");
		$this->assertEqual($user->getEmail(),null,"Default email should be blank (anonymous user)");
		$this->assertFalse($user->isMember(),"Anonymous user should not have membership rights");
		$this->assertFalse($user->isAdmin(),"Anonymous user should not have administrator rights");
		$this->assert(abs($user->getDateCreated()-$timestamp)<2,"Date created should be around now");
		$this->assert(abs($user->getLastLogin()-$timestamp)<2,"Last login should be around now");	
	
		$this->usr = $user;
		$this->expectException (new LoginException('Invalid credentials'),array(&$this,'badLogin'));
		$this->assertEqual($user->getUserID(),null,"Default userid should be null (anonymous user)");
		$this->expectException (new LoginException('Invalid credentials'),array(&$this,'badPassword'));
		$this->assertEqual($user->getUserID(),null,"Default userid should be null (anonymous user)");
	}
	
	function badLogin () {
		$this->usr->login ('fred','aaa');
	}
	function badPassword () {
		$this->usr->login ('lancem','bad');
	}
}
?>
	