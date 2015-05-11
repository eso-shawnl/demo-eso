<?php
class ControllerTicketPurchase extends Controller {
	public function index() {
		$this->load->language('ticket/purchase');
        $this->load->model('models/interface');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home'),
			'text' => $this->language->get('text_home')
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('ticket/purchase'),
			'text' => $this->language->get('heading_title')
		);

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_next'] = $this->language->get('text_next');
			$data['text_next_choice'] = $this->language->get('text_next_choice');
			$data['column_name'] = $this->language->get('column_name');
			$data['column_model'] = $this->language->get('column_model');
        $data['column_zone']=$this->language->get('column_zone');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
        $data['column_subtotal'] = $this->language->get('column_subtotal');
			$data['column_total'] = $this->language->get('column_total');
			$data['button_update'] = $this->language->get('button_update');
			$data['button_remove'] = $this->language->get('button_remove');
			$data['button_shopping'] = $this->language->get('button_shopping');
			$data['button_checkout'] = $this->language->get('button_checkout');
			$data['action'] = $this->url->link('ticket/purchase/checkout');
			$data['tickets'] = array();

        $data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', 'SSL'));
        $data['text_your_details'] = $this->language->get('text_your_details');
        $data['text_your_address'] = $this->language->get('text_your_address');
        $data['text_your_password'] = $this->language->get('text_your_password');
        $data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['text_your_details'] = $this->language->get('text_your_details');
        $data['text_your_address'] = $this->language->get('text_your_address');
        $data['text_your_password'] = $this->language->get('text_your_password');
        $data['text_your_email'] = $this->language->get('text_your_email');
        $data['text_newsletter'] = $this->language->get('text_newsletter');
        $data['text_your_telephone'] = $this->language->get('text_your_telephone');
        $data['text_your_otherinfo'] = $this->language->get('text_your_otherinfo');


        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_agegroup'] = $this->language->get('entry_agegroup');


        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');
        $data['entry_full_address'] = $this->language->get('entry_full_address');
        $data['entry_company'] = $this->language->get('entry_company');
        $data['entry_street_number'] = $this->language->get('entry_street_number');
        $data['entry_route'] = $this->language->get('entry_route');
        $data['entry_suburb'] = $this->language->get('entry_suburb');
        $data['entry_postcode'] = $this->language->get('entry_postcode');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_zone'] = $this->language->get('entry_zone');
        $data['entry_agegroup'] = $this->language->get('entry_agegroup');

        $data['entry_promotion_code'] = $this->language->get('entry_promotion_code');
        $data['entry_ticket_code'] = $this->language->get('entry_ticket_code');

        $data['entry_newsletter'] = $this->language->get('entry_newsletter');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_confirm_password'] = $this->language->get('entry_confirm_password');
        $data['entry_confirm_email'] = $this->language->get('entry_confirm_email');

        $data['text_delivery_option'] = $this->language->get('text_delivery_option');
        $data['delivery_mail'] = $this->language->get('delivery_mail');
        $data['delivery_pickup'] = $this->language->get('delivery_pickup');

        $data['online_banking'] = $this->language->get('online_banking');
        $data['offline_payment'] = $this->language->get('offline_payment');
        $data['text_payment_option'] = $this->language->get('text_payment_option');

        $data['entry_captcha_code_input'] = $this->language->get('entry_captcha_code_input');
        $data['entry_captcha_code'] = $this->language->get('entry_captcha_code');


        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_upload'] = $this->language->get('button_upload');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_upload'] = $this->language->get('button_upload');


        $data['info_firstname'] = $this->language->get('info_firstname');
        $data['info_lastname'] = $this->language->get('info_lastname');
        $data['info_agegroup'] = $this->language->get('info_agegroup');
        $data['info_email'] = $this->language->get('info_email');
        $data['info_comfirm_email'] = $this->language->get('info_comfirm_email');
        $data['info_address'] = $this->language->get('info_address');
        $data['info_promotion_code'] = $this->language->get('info_promotion_code');
        $data['info_ticket_code'] = $this->language->get('info_ticket_code');
        $data['info_telephone'] = $this->language->get('info_telephone');
        $data['text_personal_info'] = $this->language->get('text_personal_info');
        $data['button_nextstep'] = $this->language->get('button_nextstep');
        $data['text_shipping_method'] = $this->language->get('text_shipping_method');
        $data['text_pickup'] = $this->language->get('text_pickup');
        $data['text_post'] = $this->language->get('text_post');
        $data['text_shipping_detail'] = $this->language->get('text_shipping_detail');
        $data['text_payment_method'] = $this->language->get('text_payment_method');
        $data['text_online_banking'] = $this->language->get('text_online_banking');
        $data['text_onsite'] = $this->language->get('text_onsite');


        if (isset($this->request->post['customer_group_id'])) {
            $data['customer_group_id'] = $this->request->post['customer_group_id'];
        } else {
            $data['customer_group_id'] = $this->config->get('config_customer_group_id');
        }

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } else {
            $data['lastname'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['telephone'])) {
            $data['telephone'] = $this->request->post['telephone'];
        } else {
            $data['telephone'] = '';
        }

        if (isset($this->request->post['fax'])) {
            $data['fax'] = $this->request->post['fax'];
        } else {
            $data['fax'] = '';
        }

        if (isset($this->request->post['company'])) {
            $data['company'] = $this->request->post['company'];
        } else {
            $data['company'] = '';
        }

        if (isset($this->request->post['full_address'])) {
            $data['full_address'] = $this->request->post['full_address'];
        } else {
            $data['full_address'] = '';
        }

        if (isset($this->request->post['street_number'])) {
            $data['street_number'] = $this->request->post['street_number'];
        } else {
            $data['street_number'] = '';
        }

        if (isset($this->request->post['route'])) {
            $data['route'] = $this->request->post['route'];
        } else {
            $data['route'] = '';
        }

        if (isset($this->request->post['postalcode'])) {
            $data['postcode'] = $this->request->post['postalcode'];
        } else {
            $data['postcode'] = '';
        }

        if (isset($this->request->post['city'])) {
            $data['city'] = $this->request->post['city'];
        } else {
            $data['city'] = '';
        }

        if (isset($this->request->post['suburb'])) {
            $data['suburb'] = $this->request->post['suburb'];
        } else {
            $data['suburb'] = '';
        }

        if (isset($this->request->post['country_id'])) {
            $data['country'] = $this->request->post['country_id'];
        } else {
            $data['country'] = '';
        }

        if (isset($this->request->post['zone'])) {
            $data['zone'] = $this->request->post['zone'];
        } else {
            $data['zone'] = '';
        }

        if (isset($this->request->post['promotion_code'])) {
            $data['promotion_code'] = $this->request->post['promotion_code'];
        } else {
            $data['promotion_code'] = '';
        }

        if (isset($this->request->post['ticket_code'])) {
            $data['ticket_code'] = $this->request->post['ticket_code'];
        } else {
            $data['ticket_code'] = '';
        }

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }

        if (isset($this->request->post['confirm_password'])) {
            $data['confirm_password'] = $this->request->post['confirm_password'];
        } else {
            $data['confirm_password'] = '';
        }
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['firstname'])) {
            $data['error_firstname'] = $this->error['firstname'];
        } else {
            $data['error_firstname'] = '';
        }

        if (isset($this->error['lastname'])) {
            $data['error_lastname'] = $this->error['lastname'];
        } else {
            $data['error_lastname'] = '';
        }


        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        if (isset($this->error['telephone'])) {
            $data['error_telephone'] = $this->error['telephone'];
        } else {
            $data['error_telephone'] = '';
        }

        if (isset($this->error['street_number'])) {
            $data['error_street_number'] = $this->error['street_number'];
        } else {
            $data['error_street_number'] = '';
        }

        if (isset($this->error['route'])) {
            $data['error_route'] = $this->error['route'];
        } else {
            $data['error_route'] = '';
        }

        if (isset($this->error['city'])) {
            $data['error_city'] = $this->error['city'];
        } else {
            $data['error_city'] = '';
        }

        if (isset($this->error['suburb'])) {
            $data['error_suburb'] = $this->error['suburb'];
        } else {
            $data['error_suburb'] = '';
        }

        if (isset($this->error['postcode'])) {
            $data['error_postcode'] = $this->error['postcode'];
        } else {
            $data['error_postcode'] = '';
        }

        if (isset($this->error['country'])) {
            $data['error_country'] = $this->error['country'];
        } else {
            $data['error_country'] = '';
        }

        if (isset($this->error['zone'])) {
            $data['error_zone'] = $this->error['zone'];
        } else {
            $data['error_zone'] = '';
        }

        if (isset($this->error['custom_field'])) {
            $data['error_custom_field'] = $this->error['custom_field'];
        } else {
            $data['error_custom_field'] = array();
        }

        if (isset($this->error['password'])) {
            $data['error_password'] = $this->error['password'];
        } else {
            $data['error_password'] = '';
        }

        if (isset($this->error['confirm_password'])) {
            $data['error_confirm_password'] = $this->error['confirm_password'];
        } else {
            $data['error_confirm_password'] = '';
        }

        if (isset($this->error['confirm_email'])) {
            $data['error_confirm_email'] = $this->error['confirm_email'];
        } else {
            $data['error_confirm_email'] = '';
        }

        if ($this->config->get('config_account_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

            if ($information_info) {
                $data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_account_id'), 'SSL'), $information_info['title'], $information_info['title']);
            } else {
                $data['text_agree'] = '';
            }
        } else {
            $data['text_agree'] = '';
        }

        $ticket_list=array();
        $event=array('event_id'=>'50');

        //get ticket information from database
        $ticket_db=$this->model_models_interface->model_interface(0,'ticket','for_cart','get',$event);

        //get age group from database
        $age_group=$this->model_models_interface->model_interface(0,'configuration','by_code','get','age_group');

        //get delivery type from database
        $payment_method=$this->model_models_interface->model_interface(0,'configuration','by_code','get','payment_method');
        $delivery_type=$this->model_models_interface->model_interface(0,'configuration','by_code','get','delivery_type');
        $pickup_stores=$this->model_models_interface->model_interface(0,'ticket','office_by_event_id','get',50);

        foreach ($ticket_db as $key => $value) {
            if (is_array($value) && !empty($value)) {
                if ($key == 'ticket_price_list') {
                    foreach($value as $v){
                                $ticket_list[$v['row_id']]['name']=$v['ticket_level_name'];
                                $ticket_list[$v['row_id']]['price']=$v['price'];
                    }
                }
                if($key=='ticket_position_list'){
                    foreach($value as $v) {
                            $ticket_list[$v['ticket_price_row_id']]['zone'][$v['zone']] = $v['capacity'];
                        }
                }
            }
        }
        $data['tickets']=$ticket_list;
        $data['age_group']=$age_group;
        $data['payment_method']=$payment_method;
        $data['delivery_type']=$delivery_type;
        $data['stores']=$pickup_stores;
        var_dump($data['stores']);

			$data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

			//$this->load->model('extension/extension');

			$data['checkout_buttons'] = array();

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/cart.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/ticket/purchase.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/ticket/purchase.tpl', $data));
			}
	}

    public function checkout(){
        //To do - get purchase data from view
        $purchase_array=array();
        $data=$this->request->post;
        var_dump($data['customer']);

        //validate user information from form
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate($data['customer'])){
            foreach($data as $k => $v) {
                if (isset($v) && !empty($v)) {
                    if ($k != 'total' && $k != 'customer') {
                        if ($v['subtotal'] != '0.00') {
                            $purchase_array[$k] = $v;
                        }
                    }
                }
            };
            $total=$data['total'];
            $this->submit($data['customer'],$purchase_array,$total);
        }
    }
    public function submit($customer_info,$purchase_array,$total)
    {
        $this->load->model('models/interface');
        $this->load->model('account/customer');

            $data['order_id'] = 0;
            $data['event_id'] = 50; //for live - event id comes from url, otherwise set to 0
            $data['order_type_id'] = 1;
            //$data['invoice_no']
            //$data['invoice_prefix']
            //$data['store_id']
            //$data['store_name']
            //$data['store_url']
            //$data['customer_id']=$this->customer->getId();
            $data['customer_id'] = 1; // for testing
            //$data['customer_group_id']
            //$data['filter_name']
            $data['firstname'] = $customer_info['firstname'];
            $data['lastname'] = $customer_info['lastname'];
            $data['email'] = $customer_info['email'];
            $data['telephone'] = $customer_info['phone'];
            $data['fax'] = '';
            $data['payment_firstname'] = '';
            $data['payment_lastname'] = '';
            $data['payment_company'] = '';
            $data['payment_address_1'] = '';
            $data['payment_address_2'] = '';
            $data['payment_city'] = '';
            $data['payment_postcode'] = '';
            $data['payment_country'] = '';
            $data['payment_country_id'] = 0;
            //$data['payment_zone']
            //$data['payment_zone_id']
            //$data['payment_address_format']
            //$data['payment_custom_field']
            $data['payment_method'] = 1;//1 represents online banking, 2 represents EFPOS/cash
            $data['delivery_type']=1;//1 represents post, 2 represents pickup

            if($customer_info['delivery']=='delivery_mail'){
                $data['shipping_firstname'] = $customer_info['firstname'];
                $data['shipping_lastname'] = $customer_info['lastname'];
                $data['shipping_company'] = 'Beyond Media';
                $data['shipping_address_1'] = $customer_info['street_number'].' '.$customer_info['route'];
                $data['shipping_address_2'] = $customer_info['suburb'].' '.$customer_info['zone'];
                $data['shipping_city'] = $customer_info['city'];
                $data['shipping_postcode'] = $customer_info['postcode'];
                $data['shipping_country'] = $customer_info['country'];
            }
            //$data['shipping_country_id'];
            //$data['shipping_zone'];
            //$data['shipping_zone_id'];
            //$data['shipping_address_format'];
            //$data['shipping_custom_field'];
            //$data['shipping_method'];
            //$data['shipping_code'];
            $data['comment'] = 'Please call first before delivery';
            $data['total'] =$total;
            //$data['commission'];
            //$data['tracking'];
            //$data['language_id'];
            //$data['currency_id'];
            //$data['currency_code'];
            //$data['currency_value'];
            //$data['ip'];
            //$data['forwarded_ip'];
            //$data['user_agent'];
            //$data['accept_language'];
            $data['status'] = '1';
            date_default_timezone_set("UTC");
            $data['created_date'] = date("Y-m-d H:i:s", time());
            $data['modified_date'] = date("Y-m-d H:i:s", time());
            $order_array['operator_id'] = '1';

            //call stored procedure to insert a new order
            $result = $this->model_models_interface->model_interface(0, 'order', 'info', 'edit', $data);
        $order_id='';
            foreach ($result as $v) {
                if ($v['result'] == 1) {
                    $order_id = $v['reason'];
                    //populate order details from purchased items stored in session
                    $order_details = array();
                    foreach ($purchase_array as $k => $v) {
                        if ($k == 'purchase_items') {
                            foreach ($v as $k1 => $v1) {
                                $order_details[$k1]['order_row_id'] = '';
                                $order_details[$k1]['order_id'] = $order_id;
                                $order_details[$k1]['order_product_id'] = $k1;
                                $order_details[$k1]['order_product_type'] = 1;
                                $order_details[$k1]['product_quantities'] = $v1['quantity'];
                                $order_details[$k1]['product_price'] = $v1['subtotal'];
                                $order_details[$k1]['discount_rate'] = 0;
                                $order_details[$k1]['reward'] = 1;
                                $order_details[$k1]['status'] = 1;
                                $order_details[$k1]['created_date'] = date("Y-m-d H:i:s", time());
                                $order_details[$k1]['modified_date'] = date("Y-m-d H:i:s", time());
                                $order_details[$k1]['operator_id'] = '1';
                            }
                        }
                    }
                    $result1 = $this->model_models_interface->model_interface(0, 'order', 'detail', 'edit', $order_details);
                }
            }

            $items=$purchase_array;
            //parse returned result from database
            $mail = new mailservice();
            $mail_date=array();
            $mail_date['customer']['fname']=$customer_info['firstname'];
            $mail_date['customer']['lname']=$customer_info['lastname'];
            $mail_date['customer']['gender']='';
            $mail_date['customer']['salutation']='';
            $mail_date['mail']['language_id']=$this->config->get('config_language');
            $mail_date['mail']['type']='checkout';
            $mail_date['mail']['send_time']='';
            $mail_date['mail']['time']= 1;
            $mail_date['mail']['email_address']=$customer_info['email'];
            $mail_date['data']['items']=$items;
            $mail_date['data']['delivery_type']['id']='';
            $mail_date['data']['delivery_type']['value']='';
            $mail_date['data']['order_number']=$order_id;
            $mail_result = $mail->send_mail($mail_date);
            if(isset($mail_result) && !empty($mail_result)){
                foreach($mail_result as $keys => $values){
                    $mail_res =  $keys;
                    $mail_reseason =  $values;
                }
            }
        }
    public function validate($data) {
        if(isset($data['firstname'])) {
            if ((utf8_strlen(trim($data['firstname'])) < 1) || (utf8_strlen($data['firstname'])) > 32) {
                $this->error['firstname'] = $this->language->get('error_firstname');
            }
        }

        if(isset($data['lastname'])) {
            if ((utf8_strlen(trim($data['lastname'])) < 1) || (utf8_strlen(trim($data['lastname'])) > 32)) {
                $this->error['lastname'] = $this->language->get('error_lastname');
            }
        }

        if ((utf8_strlen($data['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $data['email'])) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if(isset($data['telephone'])) {
            if ((utf8_strlen($data['telephone']) < 3) || (utf8_strlen($data['telephone']) > 32)) {
                $this->error['telephone'] = $this->language->get('error_telephone');
            }
        }

        if(isset($data['route'])) {
            if ((utf8_strlen(trim($data['route'])) < 3) || (utf8_strlen(trim($data['route'])) > 128)) {
                $this->error['route'] = $this->language->get('error_route');
            }
        }

        if(isset($data['city'])) {
            if ((utf8_strlen(trim($data['city'])) < 2) || (utf8_strlen(trim($data['city'])) > 128)) {
                $this->error['city'] = $this->language->get('error_city');
            }
        }

        if(isset($data['country'])) {
            if ($data['country'] == '') {
                $this->error['country'] = $this->language->get('error_country');
            }
        }

        if(isset($data['postcode'])) {
            if ($data['postcode'] == '') {
                $this->error['postcode'] = $this->language->get('error_postcode');
            }
        }

        if(isset($data['zone'])) {
            if (!isset($data['zone']) || $data['zone'] == '') {
                $this->error['zone'] = $this->language->get('error_zone');
            }
        }
//        // Agree to terms
//        if ($this->config->get('config_account_id')) {
//            $this->load->model('catalog/information');
//
//            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));
//
//            if ($information_info && !isset($this->request->post['agree'])) {
//                $this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
//            }
//        }
        return !$this->error;
    }
}