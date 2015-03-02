<?php
/*
	This is just a thin wrapper over the PHP session
	Other implementations might store state on a database
*/
class Session implements ISession {
	function __construct() {
		session_start();
		$timestamp=time();
		if ($this->isKeySet('lastAccess')) {
			$timeSinceLastAccess=$timestamp-$this->get('lastAccess');
			if ($timeSinceLastAccess > 60*15) {  // timeout is 15 minutes
			    $this->clear();
				throw new LoginException ('Your session has expired');
			}
		}
		$this->set('lastAccess',$timestamp);
	}
	function get($key) {
		return $_SESSION[$key];
	}
	function set($key, $value) {
		$_SESSION[$key]=$value;
	}
	function isKeySet($key) {
		return isset($_SESSION[$key]);
	}
	function unsetKey($key){
		unset ($_SESSION[$key]);
	}
	function changeContext() {
		session_regenerate_id(true);
	}
	function clear() {
		foreach ($_SESSION as $key=>$value) {
			unset($_SESSION[$key]);
		}		
		session_destroy();
	}
}
?>
