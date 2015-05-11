<?php
class ControllerTicketCheckout extends Controller
{
    public function index()
    {
        $this->load->model('models/interface');
        $this->load->model('account/customer');

        //if user is not logged, redirect to register page
        if (!$this->customer->isLogged()) {
            if (!empty($this->session->data['purchase_items'])) {
                $purchase_items = $this->session->data['purchase_items'];

                $data['purchase_items'] = $purchase_items;
                //get user address from database;
                $address_list = $this->model_account_customer->get_customer_address(64);
                $data['address_list'] = $address_list;
            }
            var_dump($data['purchase_items']);
        }
        else{
            $this->response->redirect($this->url->link('account/register', '', 'SSL'));
        }
        $this->load->language('checkout/checkout');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

        // Required by klarna
        if ($this->config->get('klarna_account') || $this->config->get('klarna_invoice')) {
            $this->document->addScript('http://cdn.klarna.com/public/kitt/toc/v1.0/js/klarna.terms.min.js');
        }

        $data['action'] = $this->url->link('ticket/checkout/submit');
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_cart'),
            'href' => $this->url->link('checkout/cart')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('checkout/checkout', '', 'SSL')
        );

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_checkout_option'] = $this->language->get('text_checkout_option');
        $data['text_checkout_account'] = $this->language->get('text_checkout_account');
        $data['text_checkout_payment_address'] = $this->language->get('text_checkout_payment_address');
        $data['text_checkout_shipping_address'] = $this->language->get('text_checkout_shipping_address');
        $data['text_checkout_shipping_method'] = $this->language->get('text_checkout_shipping_method');
        $data['text_checkout_payment_method'] = $this->language->get('text_checkout_payment_method');
        $data['text_checkout_confirm'] = $this->language->get('text_checkout_confirm');


        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/ticket/checkout.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/ticket/checkout.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/ticket/checkout.tpl', $data));
        }
    }

    public function submit()
    {
        $this->load->model('models/interface');
        $this->load->model('account/customer');
        $post_data=array();
        $purchase_items = $this->session->data['purchase_items'];
        $flag = 1;
        if ($flag == 1) {
            //To do- get live data from view about payment selection and delivery address
            //For testing - using fake data below
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
            $data['firstname'] = 'Steven';
            $data['lastname'] = 'Wang';
            $data['email'] = 'yuqian.m.lu@gmail.com';
            $data['telephone'] = '06121321312';
            $data['fax'] = '2131231';
            $data['payment_firstname'] = 'Michael';
            $data['payment_lastname'] = 'Lu';
            $data['payment_company'] = 'ESO New Zealand';
            $data['payment_address_1'] = '40 Eden Street';
            $data['payment_address_2'] = 'Takapuna';
            $data['payment_city'] = 'Auckland';
            $data['payment_postcode'] = '1010';
            $data['payment_country'] = 'New Zealand';
            $data['payment_country_id'] = 0;
            //$data['payment_zone']
            //$data['payment_zone_id']
            //$data['payment_address_format']
            //$data['payment_custom_field']
            $data['payment_method'] = 1;//1 represents online banking
            //$data['payment_code'];
            $data['shipping_firstname'] = 'Shawn';
            $data['shipping_lastname'] = 'Lin';
            $data['shipping_company'] = 'Beyond Media';
            $data['shipping_address_1'] = '32 Eden Cresent';
            $data['shipping_address_2'] = 'Auckland Central';
            $data['shipping_city'] = 'Auckland';
            $data['shipping_postcode'] = '1213';
            $data['shipping_country'] = 'New Zealand';
            //$data['shipping_country_id'];
            //$data['shipping_zone'];
            //$data['shipping_zone_id'];
            //$data['shipping_address_format'];
            //$data['shipping_custom_field'];
            //$data['shipping_method'];
            //$data['shipping_code'];
            $data['comment'] = 'Please call first before delivery';
            $data['total'] =1250;
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
            foreach ($result as $v) {
                if ($v['result'] == 1) {
                    $order_id = $v['reason'];
                    //populate order details from purchased items stored in session
                    $order_details = array();
                    foreach ($purchase_items as $k => $v) {
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
                    var_dump($result1);
                    var_dump($order_details);
                }
            }

            $items=array(
                array('name'=>'VVIP','price'=>1280, 'quantity'=>2,'zone'=>'102'),
                array('name'=>'A','price'=>326, 'quantity'=>3,'zone'=>'263'));

            //parse returned result from database
                    $mail = new mailservice();
                    $mail_date=array();
                    $mail_date['customer']['fname']='Steven';
                    $mail_date['customer']['lname']='Wang';
                    $mail_date['customer']['gender']='';
                    $mail_date['customer']['salutation']='';
                    $mail_date['mail']['language_id']='en';
                    $mail_date['mail']['type']='checkout';
                    $mail_date['mail']['send_time']='';
                    $mail_date['mail']['time']= 1;
                    $mail_date['mail']['email_address']='yuqian.m.lu@gmail.com';
                    $mail_date['data']['items']=$items;
                    $mail_date['data']['delivery_type']['id']='';
                    $mail_date['data']['delivery_type']['value']='';
                    $mail_date['data']['order_number']='13';
                    $mail_result = $mail->send_mail($mail_date);
                    if(isset($mail_result) && !empty($mail_result)){
                        foreach($mail_result as $keys => $values){
                            $mail_res =  $keys;
                            $mail_reseason =  $values;
                        }
                    }

        }
    }
}