<?php
	function get_randnumber($type){
		if($type=='serialno'){
			return $randnumber=date("Ymd-His") . '-' . rand(1000,9999);
		}
		else if($type=='product'){
			return $randnumber=rand(1000,9999) . '-' . rand(10,99).'-' . rand(1000,9999);
		}
		else if($type=='order'){
			return $randnumber=date("Ymd-His") . '-' . rand(10,99).'-' . rand(100,999);
		}
		else if($type=='custom'){
			return $randnumber=date("Ymd-His") . '-' . rand(100,999).'-' . rand(100,999);
		}
	}

	function writeGetUrlInfo($msg)
	{
		$logFile = './log/'.date('Y-m-d').'.txt';
		$msg = date('Y-m-d H:i:s').' >>> '.$msg."\r\n";
		file_put_contents($logFile,$msg,FILE_APPEND );
	}
	
	function check_sqlinjection($value,$type){
		if($type!='email'){
			$pattern='^\w+$';
			//if(preg_match("^[A-Za-z0-9]+$", $value)==1 || preg_match("^[^%&<>,;=?$\x22]+$", $value)==1 || preg_match("^[A-Za-z0-9]+$", $value)==1)
			if(preg_match($pattern, $value,$matches))
				return true;
			else 
				return false;
		}
		else{
			if(preg_match('^\w+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$', $value,$matches))
				return true;
			else
				return false;
		}
	}
	
	function validateEmail($value)
	{
		return ereg("^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+", $value);
	}
	
	function validatePassword($value)
	{
		return ereg("^[A-Za-z0-9_]{6,30}$", $value);
	}

	function validateAddress($value)
	{
		return ereg("^[a-zA-Z0-9]{1,30}$", $value);
	}
	
	function validatePostcode($value)
	{
		return ereg("^[0-9]{1,6}$", $value);
	}
	
	function validateName($value)
	{
		return ereg("^[A-Za-z]{1,12}$", $value);
	}
	
	function validateDate($value)
	{
		return ereg("^[0-9]{1,4}$", $value);
	}
	
	function validatePhone($value)
	{
		return ereg("^[0-9]{1,12}$", $value);
	}
	
	function validateSearch($value)
	{
		return ereg("^[A-Za-z0-9_]+$", $value);
	}
	
	function getFileList(){
		$ignore = array();
		
		$data['filelist'] = array();
		
		$files = glob(DIR_APPLICATION . 'controller/*/*.php');
		
		foreach ($files as $file) {
			$part = explode('/', dirname($file));
		
			$permission = end($part) . '/' . basename($file, '.php');
		
			if (!in_array($permission, $ignore)) {
				$data['filelist'][] = 'admin/'.$permission;
			}
		}
		$files = glob(DIR_CATALOG . 'controller/*/*.php');
		
		foreach ($files as $file) {
			$part = explode('/', dirname($file));
		
			$permission = end($part) . '/' . basename($file, '.php');
		
			if (!in_array($permission, $ignore)) {
				$data['filelist'][] = 'catalog/'.$permission;
			}
		}
		return $data['filelist'];
	}
	
	/*get page link from url   */
	function getSubdomain($url,$domain){
		$url=str_replace ("http://", "", $url);
		$url=str_replace ("www.", "", $url);
		/*
			$subdomain=str_replace (".".$domain, "", $subdomain);
			$subdomain=str_replace ("'", "", $subdomain);
			$subdomain=str_replace ("@", "", $subdomain);
			$subdomain=str_replace ("=", "", $subdomain);
			$subdomain=str_replace (";", "", $subdomain);
			$subdomain=str_replace ("/", "", $subdomain);
		*/
		return $url;
	}
	
	//get client IP
	function getIP(){
		$mysqli = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

        $ip = $_SERVER['REMOTE_ADDR']; // the IP address to query
		$query = serialize(file_get_contents('http://ip-api.com/php/'.$ip));

		$mysqli->query("call addIPAddress('$ip','$query',@result,@resson)");
		
	}
	
	function getRealIP() {
		 if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		  $ip = getenv("HTTP_CLIENT_IP");
		 else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
		  $ip = getenv("HTTP_X_FORWARDED_FOR");
		 else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		  $ip = getenv("REMOTE_ADDR");
		 else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		  $ip = $_SERVER['REMOTE_ADDR'];
		 else
		  $ip = "unknown";
		 return($ip);
				
	}
	
	//Get an array with geoip-infodata
	function geoFromIpinfoIo($ip){
		/*
			"ip": "8.8.8.8",
			"hostname": "google-public-dns-a.google.com",
			"loc": "37.385999999999996,-122.0838",
			"org": "AS15169 Google Inc.",
			"city": "Mountain View",
			"region": "California",
			"country": "US",
			"phone": 650
			*/
		$ipInfo = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
		return $ipInfo;
	}
	
	
	function geoFromIpapiCom($ip){
		$ipInfo = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
		return $ipInfo;
	}
?>