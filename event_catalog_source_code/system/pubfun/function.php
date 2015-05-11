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


    function Url_Encode($url)  {
    	//return $url;
        $encode_url=string2secret($url);

        return $encode_url;
    }

    function Url_Decode($url)  {
    	//return $url;
        $_array = explode('&amp;',secret2string($url));

        $edcode_url=array();

        if(!empty($_array)){
            foreach($_array as $value){
                $temp_aray=explode('=',$value);
                if(is_array($temp_aray) && count($temp_aray)==2){
                    $edcode_url[$temp_aray[0]]=$temp_aray[1];
                }
            }
        }
        else{

        }

        return $edcode_url;

    }

    //encode
    function string2secret($txtStream)
    {
        $password='123';
        $lockstream = 'st=lDEFABCNOPyzghi_jQRST-UwxkVWXYZabcdef+IJK6/7nopqr89LMmGH012345uv';

        $lockLen = strlen($lockstream);
        $lockCount = rand(0,$lockLen-1);
        $randomLock = $lockstream[$lockCount];

        $password = md5($password.$randomLock);

        $txtStream = base64_encode($txtStream);

        $tmpStream = '';

        $k = 0;

        for ($i=0; $i<strlen($txtStream); $i++) {
            $k = ($k == strlen($password)) ? 0 : $k;
            $j = (strpos($lockstream,$txtStream[$i])+$lockCount+ord($password[$k]))%($lockLen);
            $tmpStream .= $lockstream[$j];
            $k++;
        }
        return $tmpStream.$randomLock;
    }

    //decode
    function secret2string($txtStream)
    {
        $password='123';
        $lockstream = 'st=lDEFABCNOPyzghi_jQRST-UwxkVWXYZabcdef+IJK6/7nopqr89LMmGH012345uv';

        $lockLen = strlen($lockstream);

        $txtLen = strlen($txtStream);

        $randomLock = $txtStream[$txtLen - 1];

        $lockCount = strpos($lockstream,$randomLock);

        $password = md5($password.$randomLock);

        $txtStream = substr($txtStream,0,$txtLen-1);
        $tmpStream = '';

        $k = 0;
        for($i=0; $i<strlen($txtStream); $i++){
            $k = ($k == strlen($password)) ? 0 : $k;
            $j = strpos($lockstream,$txtStream[$i]) - $lockCount - ord($password[$k]);
            while($j < 0){
                $j = $j + ($lockLen);
            }
            $tmpStream .= $lockstream[$j];
            $k++;
        }
        return base64_decode($tmpStream);
    }

    function after ($this, $inthat)
    {
        if (!is_bool(strpos($inthat, $this)))
            return substr($inthat, strpos($inthat,$this)+strlen($this));
    };

    function after_last ($this, $inthat)
    {
        if (!is_bool(strrevpos($inthat, $this)))
            return substr($inthat, strrevpos($inthat, $this)+strlen($this));
    };

    function before ($this, $inthat)
    {
        return substr($inthat, 0, strpos($inthat, $this));
    };

    function before_last ($this, $inthat)
    {
        return substr($inthat, 0, strrevpos($inthat, $this));
    };

    function between ($this, $that, $inthat)
    {
        return before ($that, after($this, $inthat));
    };

    function between_last ($this, $that, $inthat)
    {
        return after_last($this, before_last($that, $inthat));
    };

    // use strrevpos function in case your php version does not include it
    function strrevpos($instr, $needle)
    {
        $rev_pos = strpos (strrev($instr), strrev($needle));
        if ($rev_pos===false) return false;
        else return strlen($instr) - $rev_pos - strlen($needle);
    };
?>
