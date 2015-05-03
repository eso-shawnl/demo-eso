<?php
class Language {
	private $default = 'english';
	private $directory;
	private $data = array();

	public function __construct($directory = '') {
		$this->directory = $directory;
	}

	public function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : $key);
	}
	
        /*
	public function load($filename) {
		$_ = array();

		$file = DIR_LANGUAGE . $this->default . '/' . $filename . '.php';

		if (file_exists($file)) {
			require($file);
		}

		$file = DIR_LANGUAGE . $this->directory . '/' . $filename . '.php';

		if (file_exists($file)) {
			require($file);
                        
		}
                

		$this->data = array_merge($this->data, $_);
                
		return $this->data;
                
	}
        */
        
        
    //add by steven used to load language data from DB
    public function load($filename) {

        $aa = array();

        $file = LANG . '/'.$filename . '.php';

        $directory=$this->directory;

        $mysqli = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

        //$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        //run the store proc
        //print_r($file.$directory);
        $result = $mysqli->query("CALL getCaptionByPageAndLang('$file','$directory')")
                or die("Query fail: " . mysqli_error($mysqli));
        $result = get_object_vars($result);

        //print_r($result);
        //loop the result set

        foreach ($result as $key => $value) {
            if($key=='rows'){
                foreach ($value as $val) {
                    $aa[$val['name']]=StripSlashes($val['value']);
                }
            }
        }

        $this->data = array_merge($this->data, $aa);

        return $this->data;
     

	}
        //
        

}
