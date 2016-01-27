<?php
class LogboostSession 
{
	protected $id ;
	protected $sid ;
	protected $authcode ;
	protected $username ;
	protected $ip ;
	protected $date ;
	protected $validuntil ;
	protected $plan ;
	protected $oidc ;

	public function __construct($redirect) {
		if(!isset($GLOBALS['Logboost_clientID']) || !isset($GLOBALS['Logboost_clientSecret']))
			throw new UnableToConnectException('Client id or secret id not specified') ;

		$this->oidc = new OpenIDConnectClient('http://logboost.com/',$GLOBALS['Logboost_clientID'],$GLOBALS['Logboost_clientSecret']);
		$this->oidc->addScope("openid profile payment") ;
		if($redirect != null) {
			$this->oidc->setRedirectURL($redirect);
		} 
	}

	public function __get($property) {
		if('id' === $property) {
		  return $this->id;
	    } else if('sid' === $property) {
	      return $this->sid;
	    } else if('authcode' === $property) {
			return $this->authcode;
	    } else if('username' === $property) {
	    	return $this->username;
	    } else if('ip' === $property) {
	    	return $this->ip;
	    } else if('date' === $property) {
	    	return $this->date;
	   	} else if('validuntil' === $property) {
	    	return $this->validuntil;
	    } else if('plan' === $property) {
	    	return $this->plan;
	    } else if('oidc' === $property) {
	    	return $this->oidc;
	    } else {
	      throw new Exception('Invalid property');
	    }
	}

	public function __set($property,$value) {
		if('id' === $property) {
		  $this->id = $value; 
	    } else if('sid' === $property) {
	      $this->sid = $value;  
	    } else if('authcode' === $property) {
	      $this->authcode = $value; 
	    } else if('username' === $property) {
	      $this->username = $value; 
	    } else if('ip' === $property) {
	      $this->ip = $value; 
	    } else if('date' === $property) {
	      $this->date = $value; 
	    } else if('validuntil' === $property) {
	      $this->validuntil = $value; 
	    } else if('plan' === $property) {
	      $this->plan = $value; 
	    } else if('oidc' === $property) {
	      $this->oidc = $value; 
	    } else {
	      throw new Exception('Invalid property '.$property);
	    }
  	}

  	function openSession() {
		$this->oidc->authenticate();
  	}

  	function handleSession() {
  		$this->sid = session_id() ;		
		$this->username = $this->oidc->requestUserInfo('preferred_username');
		$this->ip = $_SERVER['REMOTE_ADDR'] ;
		$this->date = @date('c') ;
		$this->validuntil = $this->oidc->requestUserInfo('valid_until');
		$this->plan = $this->oidc->requestUserInfo('plan');
  	}

  	function isPremium() {
  		if($this->plan & 0001 == 1) {
  			if(new DateTime($this->validuntil) > new DateTime("now")) {
  				return true ;
  			}
  		}

  		return false ;
  	}
}
?>