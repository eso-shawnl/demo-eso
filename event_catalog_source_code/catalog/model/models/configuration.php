<?php
class Configuration extends Model {

    public function get_configuration_by_code($user,$code) {

        $result=array();

        if(!empty($code)){

            $query = $this->db->multi_query("CALL get_configuration_by_code('$code')");
            foreach ($query as $key =>$value) {
                $result[$value['code']][$value['key']]=$value['value'];
            }
        }
        else{

        }
        return $result;

    }

}
