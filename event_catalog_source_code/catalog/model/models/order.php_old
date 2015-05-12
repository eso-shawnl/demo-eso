<?php
class Order extends Model {

    public function merge_data($old_array = array(),$update_array = array()){

        $temp_array =  array();

        if(is_array($update_array) && isset($update_array) && !empty($update_array)) {

            foreach ($update_array as $update_key => $update_value) {

                foreach($update_value as $k=>$v){
                    $temp_array[$update_key][$k]=$v;
                }
                $temp_array[$update_key]['row_id']=$update_key;
                $temp_array[$update_key]['status']=1;

                foreach($old_array as $keys=>$values){

                    if(array_key_exists($update_key,$values)){
                        break;
                    }
                    else{
                        $temp_array[$update_key]['data_status']='insert';
                        $temp_array[$update_key]['status']=1;
                    }
                }
            }
        }

        if(is_array($old_array) && isset($old_array) && !empty($old_array)){

            foreach($old_array as $keys=>$values){

                if(array_key_exists($values['row_id'],$update_array)){
                    $temp_array[$values['row_id']]['data_status']='update';
                    $temp_array[$values['row_id']]['status']=1;
                }
                else{
                    foreach($values as $old_k=>$old_val){
                        $temp_array[$values['row_id']][$old_k]= $old_val;
                    }
                    $temp_array[$values['row_id']]['data_status']='delete';
                    $temp_array[$values['row_id']]['status']=0;
                }
            }

        }

        return $temp_array;
    }

    public function get_order_for_customer($user,$data = array()) {

        $result=array();

        $order_id           = $this->db->escape($data['order_id']);

        $status             = $this->db->escape($data['status']);

        if($order_id > 0){

            $query = $this->db->multi_query("CALL get_order_for_customer($order_id,$user,$status)");

            if(!empty($query)){
                $result['order'] =$query;
            }
            else{
                $result['order'] =null;
            }
        }
        else {
            $result['order'] =null;
        }

        return $result;

    }

    public function get_order_detail_for_customer($user,$data = array()) {

        $result=array();

        $order_id           = $this->db->escape($data['order_id']);

        $status             = $this->db->escape($data['status']);

        $order_detail_id    = $this->db->escape(isset($data['order_detail_id']) ? $data['order_detail_id'] : 0);

        if($order_id > 0 && $order_detail_id >=0){

            $query = $this->db->multi_query("CALL get_order_detail_for_customer($order_id,$order_detail_id,$user,$status)");

            if(!empty($query)){
                $result['order_detail'] =$query;
            }
            else{
                $result['order_detail'] =null;
            }
        }
        else {
            $result['order_detail'] =null;
        }


        return $result;

    }

    public function get_order_by_id($order_id) {

        $result=array();

        $order_id   = $this->db->escape($order_id);

        if($order_id > 0){

            $query = $this->db->multi_query("CALL get_order_by_ID_Status($order_id)");

        }
        else {

        }
        if(!empty($query)){
            $result['order'] =$query;
        }
        else{
            $result['order'] =null;
        }

        return $result;

    }

    public function get_order_detail_by_id($order_deatail_row_id) {

        $result=array();

        $order_deatail_row_id   = $this->db->escape($order_deatail_row_id);

        if($order_deatail_row_id > 0){

            $query = $this->db->multi_query("CALL get_order_by_ID_Status($order_deatail_row_id)");

        }
        else {

        }
        if(!empty($query)){
            $result['order_deatail'] =$query;
        }
        else{
            $result['order_deatail'] =null;
        }

        return $result;

    }

    public function edit_order_info($user,$data = array()) {

        $result = array();

        if(is_array($data) && !empty($data) && $user>=0) {

            $order_id = $this->db->escape(isset($data['order_id']) ? $data['order_id'] : 0);
            $order_type_id = $this->db->escape(isset($data['order_type_id']) ? $data['order_type_id'] : 0);
            $invoice_no = $this->db->escape(isset($data['invoice_no']) ? $data['invoice_no'] : '');
            $invoice_prefix = $this->db->escape(isset($data['invoice_prefix']) ? $data['invoice_prefix'] : '');
            $customer_id = $this->db->escape(isset($data['customer_id']) ? $data['customer_id'] : 0);
            $customer_group_id = $this->db->escape(isset($data['customer_group_id']) ? $data['customer_group_id'] : 0);
            $firstname = $this->db->escape(isset($data['firstname']) ? $data['firstname'] : '');
            $lastname = $this->db->escape(isset($data['lastname']) ? $data['lastname'] : '');
            $email = $this->db->escape(isset($data['email']) ? $data['email'] : '');
            $telephone = $this->db->escape(isset($data['telephone']) ? $data['telephone'] : '');
            $shipping_firstname = $this->db->escape(isset($data['shipping_firstname']) ? $data['shipping_firstname'] : '');
            $shipping_lastname = $this->db->escape(isset($data['shipping_lastname']) ? $data['shipping_lastname'] : '');
            $shipping_company = $this->db->escape(isset($data['shipping_company']) ? $data['shipping_company'] : '');
            $shipping_address_1 = $this->db->escape(isset($data['shipping_address_1']) ? $data['shipping_address_1'] : '');
            $shipping_address_2 = $this->db->escape(isset($data['shipping_address_2']) ? $data['shipping_address_2'] : '');
            $shipping_city = $this->db->escape(isset($data['shipping_city']) ? $data['shipping_city'] : '');
            $shipping_postcode = $this->db->escape(isset($data['shipping_postcode']) ? $data['shipping_postcode'] : '');
            $shipping_country = $this->db->escape(isset($data['shipping_country']) ? $data['shipping_country'] : '');
            $shipping_country_id = $this->db->escape(isset($data['shipping_country_id']) ? $data['shipping_country_id'] : 0);
            $shipping_zone = $this->db->escape(isset($data['shipping_zone']) ? $data['shipping_zone'] : '');
            $shipping_zone_id = $this->db->escape(isset($data['shipping_zone_id']) ? $data['shipping_zone_id'] : 0);
            $shipping_address_format = $this->db->escape(isset($data['shipping_address_format']) ? $data['shipping_address_format'] : '');
            $shipping_custom_field = $this->db->escape(isset($data['shipping_custom_field']) ? $data['shipping_custom_field'] : '');
            $shipping_method = $this->db->escape(isset($data['shipping_method']) ? $data['shipping_method'] : '');
            $shipping_code = $this->db->escape(isset($data['shipping_code']) ? $data['shipping_code'] : '');
            $comment = $this->db->escape(isset($data['comment']) ? $data['comment'] : '');
            $total = $this->db->escape(isset($data['total']) ? $data['total'] : '');
            $currency_id = $this->db->escape(isset($data['currency_id']) ? $data['currency_id'] : 1);
            $currency_code = $this->db->escape(isset($data['currency_code']) ? $data['currency_code'] : '');
            $currency_value = $this->db->escape(isset($data['currency_value']) ? $data['currency_value'] : '');
            $status = $this->db->escape(isset($data['status']) ? $data['status'] : 1);
            $created_date = $this->db->escape(isset($data['created_date']) ? $data['created_date'] : '0000-00-00');
            $modified_date = $this->db->escape(isset($data['modified_date']) ? $data['modified_date'] : '0000-00-00');
            $operator_id = $this->db->escape($user);


            $this->db->query("call edit_order($order_id,
                                            $order_type_id,
                                            '$invoice_no',
                                            '$invoice_prefix',
                                            $customer_id,
                                            $customer_group_id,
                                            '$firstname',
                                            '$lastname',
                                            '$email',
                                            '$telephone',
                                            '$shipping_firstname',
                                            '$shipping_lastname',
                                            '$shipping_company',
                                            '$shipping_address_1',
                                            '$shipping_address_2',
                                            '$shipping_city',
                                            '$shipping_postcode',
                                            '$shipping_country',
                                            $shipping_country_id,
                                            '$shipping_zone',
                                            $shipping_zone_id,
                                            '$shipping_address_format',
                                            '$shipping_custom_field',
                                            '$shipping_method',
                                            '$shipping_code',
                                            '$comment',
                                            $total,
                                            $currency_id,
                                            '$currency_code',
                                            '$currency_value',
                                            $status,
                                            '$created_date',
                                            '$modified_date',
                                            $operator_id,
                                            @result,@reason)");

            $_result = get_object_vars($this->db->query("SELECT @result"));

            $_reason = get_object_vars($this->db->query("SELECT @reason"));

            $result[$data['order_id']] = array('result' => $_result['row']['@result'], 'reason' => $_reason['row']['@reason']);
        }
        return $result;

    }

    public function edit_order_detail($user,$data = array()) {

        $result = array();

        if(is_array($data) && !empty($data) && $user>=0) {

            foreach ($data as $key => $value) {

                $order_detail_row_id = $this->db->escape(isset($value['order_detail_row_id']) ? $value['order_detail_row_id'] : 0);
                $order_id = $this->db->escape(isset($value['order_id']) ? $value['order_id'] : 0);
                $order_product_id = $this->db->escape(isset($value['order_product_id']) ? $value['order_product_id'] : 1);
                $order_product_type = $this->db->escape(isset($value['order_product_type']) ? $value['order_product_type'] : 1);
                $product_quantities = $this->db->escape(isset($value['product_quantities']) ? $value['product_quantities'] : 0);
                $product_price = $this->db->escape(isset($value['product_price']) ? $value['product_price'] : 0);
                $discount_rate = $this->db->escape(isset($value['discount_rate']) ? $value['discount_rate'] : 1);
                $reward = $this->db->escape(isset($value['reward']) ? $value['reward'] : 1);

                $status = $this->db->escape(isset($value['status']) ? $value['status'] : 0);
                $created_date = $this->db->escape(isset($value['created_date']) ? $value['created_date'] : '0000-00-00');
                $modified_date = $this->db->escape(isset($value['modified_date']) ? $value['modified_date'] : '0000-00-00');
                $operator_id = $this->db->escape($user);

                $this->db->query("call edit_order_detail($order_detail_row_id,$order_id,$order_product_id,'$order_product_type',$product_quantities,$product_price,
                $discount_rate,$reward,$status,'$created_date','$modified_date',$operator_id,@result,@reason)");

                $_result = get_object_vars($this->db->query("SELECT @result"));

                $_reason = get_object_vars($this->db->query("SELECT @reason"));

                $result[] = array('result' => $_result['row']['@result'], 'reason' => $_reason['row']['@reason']);

            }
        }

        return $result;

    }
    /*
    public function check_order($user,$event_id,$order = array()) {

        if((is_array($order) && isset($order) && !empty($order)) &&

            $user>0 && $event_id >0) {

            //校验event 是否存在，如果不存在，返回false

            if($this->model_models_interface->model_interface($user,'Event','TicketByEIDAndTID','check',$order)){

            }
            else{

                return false;

            }
            //校验order是否存在，如果存在，判断是否属于该客户，如果不存在，新建order
            $order_id = $order['order_id'];

            $check_order = $this->get_order_by_id($order_id);

            if(!empty($check_order)){

                if($check_order['customer_id'] != $user){

                    return false;

                }
                else{

                    //获取
                    $diff_order_info = array_diff_assoc($order,$check_order);

                    $same_order_info = array_intersect_assoc($check_order,$order);

                    return array_merge($same_order_info,$diff_order_info);
                }

            }
            else{
                return $order;
            }

        }
        else {
            return false;
        }
    }

    public function check_order_detail($user,$event_id,$order_detail = array()) {

        if((is_array($order_detail) && isset($order_detail) && !empty($order_detail)) &&
            $user>=0 && $event_id >=0) {

            foreach($order_detail as $key => $value){

                //校验event 是否存在，如果不存在，返回false
                $data['order_detail'] = $this->get_order_detail_for_customer($order_id,$order_detail_id,$user,1) ;

                if(isset($data['order_detail']) && !empty($data['order_detail'])){

                }
                else{

                    return false;

                }
                //校验order是否存在，如果存在，判断是否属于该客户，如果不存在，新建order
                $order_id = $order['order_id'];

                $check_order = $this->get_order_by_ID_Status($order_id,1);

                if(!empty($check_order)){

                    if($check_order['customer_id'] != $user){

                        return false;

                    }
                    else{

                        //获取
                        $diff_order_info = array_diff_assoc($order,$check_order);

                        $same_order_info = array_intersect_assoc($check_order,$order);

                        return array_merge($same_order_info,$diff_order_info);
                    }

                }
                else{
                    return $order;
                }
            }


        }
        else {

            return false;

        }
    }
    */
    public function edit_order_interface($user,$data = array()) {

        $result = array();

        if((is_array($data['order']) && isset($data['order']) && !empty($data['order'])) &&
            (is_array($data['order_detail']) && isset($data['order_detail']) && !empty($data['order_detail'])) &&
            $user>0 &&
            (isset($data['event_id']) && !empty($data['event_id']))) {


            //if ($this->check_order($data['event_id'],$data['order']) &&
            //    $this->check_order_detail($data['event_id'],$data['order_detail'])) {

                $this->edit_order($user,$data['order']);
                $this->edit_order_detail($user,$data['order_detail']);

            //}
            //else{
            //    $result = array('result'=>false,'reason'=>'data is incorrect');
            //}
        }
        else{
            $result = array('result'=>false,'reason'=>'data is incorrect');
        }
        return $result;

    }
}
