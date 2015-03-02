<?php

// add setPassword etc

class User {
	private $db;			// object
	private $session;		// object
	
	private $uid;			// integer
	private $name;			// string
	private $email;			// string
	private $isMember;		// boolean
	private $isAdmin;		// lazy boolean
	private $dateCreated; 	// stored as unix timestamp
	private $lastLogin;  	// stored as unix timestamp
	
	public function __construct ($context){
		$this->db=$context->getDB();
		$this->session=$context->getSession();
		$this->init();
	}
	private function init() {
		$this->uid=null; 
		$this->name='';   		// default for anonymous user
		$this->email=null; 		// default for anonymous user
		$this->isMember=false;  // default false
		$this->isAdmin=null;    // default unchecked
		$this->dateCreated=time();
		$this->lastLogin=time();

		if ($this->session->isKeySet('userID')) {
			$user=$this->session->get('userID');
			$sql="select name, email, dateCreated, lastLogin from users where userID=$user";
			$result=$this->db->query($sql);
			if (count($result)==1) {
				$this->uid=$user;	
				$this->isMember=true;
				$row=$result[0];
				$this->name=$row['name'];
				$this->email=$row['email'];
				$this->dateCreated=strtotime($row['dateCreated']);
				$this->lastLogin=strtotime($row['lastLogin']);
			}
		}	
	}	
	public function getUserID() {
		return $this->uid;
	}
	public function getName() {
		return $this->name;
	}
	public function getEmail() {
		return $this->email;
	}
	public function getDateCreated() {
		return $this->dateCreated;
	}
	public function getLastLogin() {
		return $this->lastLogin;
	}	
	public function isMember() {	
		return $this->isMember;
	}		
	/* 
		This is an example of 'lazy' initialisation.
		There's no point in hitting the administrators table unless
		someone needs to know so we do it "just in time".
	*/
	public function isAdmin() {
		if ($this->isAdmin==null) {
			if ($this->uid==null) {
				$this->isAdmin=false;
			} else {		
				$sql="select userID from administrators where userID=$this->uid";
				$result=$this->db->query($sql);
				$this->isAdmin=count($result)==1;
			}
		}
		return $this->isAdmin;
	}	
	/*
		@return: false if invalid
		         userID if valid
	*/
	public static function isValidLogin($db, $email, $password) {
		if ($email===null || $password===null || $email==='' || $password==='') {
			sleep (1);
			return false;
		}
		/* Adding a sleep of one second to invalid logins massively increases the cost
			to attackers of a brute force password attack with minimal inconvenience to
			genuine users.
		*/
		
		$email=$db->escape($email);	// protect against SQL injection!;
		$sql="select userID, pwCheck from users where email='$email'";
		$result=$db->query($sql);
		if (count($result)!==1) {
			sleep (1);
			return false;
		}
		$row=$result[0];
		$userID=$row['userID'];
		$check=$row['pwCheck'];
		
		$input=sha1($password.'security 101');  // just a tad more salt!
		
		if ($check==null) {
			echo '<br/>pWord: '.$password;
			echo '<br/>Input: '.$input;
			echo '<br/>Check: '.User::createPasswordCheck($input);
		}
	
		if (crypt($input, $check) == $check){
			sleep(1);
			return false;
		}
		
		return $userID;
	}
	public function login($email, $password) {
		$userID=$this->isValidLogin($this->db, $email, $password);
		if ($userID===false) {
			throw new LoginException ('Invalid credentials');
		}
		
		$timestamp=date('Y-m-d H:i:s',time());
		$sql="update users set lastLogin='$timestamp;'";
		$this->db->execute($sql);
		
		$this->session->set('userID',$userID);
		$this->session->changeContext();
		$this->init();
	}
	public function logout() {
		$this->session->unsetKey('userID');
		$this->session->changeContext();
		$this->init();
	}
		// output should be 75 chars 
	private static function createPasswordCheck ($password) {
		$input=sha1($password.'security 101');
		$salt='';  // Tasty Salt and Vinegar
		// salt alphabet
	    $chars='./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		// generate random salt
		for($i=0;$i<16;$i++) $salt.=$chars[mt_rand(0,63)]; 
		// let crypt do the heavy lifting
		return crypt($input,'$5$rounds=5000$'.$salt);
	}	
}
?>
