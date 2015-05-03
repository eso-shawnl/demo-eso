<?php
/**
 * Created by PhpStorm.
 * User: steven
 * Date: 4/1/15
 * Time: 17:25
 */

class ControllerLocalisationUploaddescription extends Controller {

    public function index() {

        $file_list=array(
            DIR_CATALOG.'language/*/*/*.php',
            DIR_CATALOG.'language/*/*.php',
            DIR_CATALOG.'language/*/*/*.php',
            DIR_CATALOG.'language/*/*.php',
            DIR_APPLICATION.'language/*/*/*.php',
            DIR_APPLICATION.'language/*/*.php',
            DIR_APPLICATION.'language/*/*/*.php',
            DIR_APPLICATION.'language/*/*.php');

        $i=0;
        foreach ($file_list as $value) {

            $files1 = glob($value);

            foreach ($files1 as $file) {

                $_=array();

                if (file_exists($file)) {

                    require($file);

                }

                foreach ($_ as $key =>$val) {

                    $this->db->query('insert into temp_language(filename,title,value) '
                        . 'values(\''.$file.'\',\''.$key.'\',\''.addslashes($val).'\')');
                    //addslashes($val)
                    $i++;
                }
            }

        }
        echo $i;
    }
}
