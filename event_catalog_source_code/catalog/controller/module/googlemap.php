<?php
class ControllerModuleGoogleMap extends Controller {
	public function index() {
		$this->load->language('module/googlemap');

        $data=array();

        $this->load->model('catalog/product');

        $product_info = $this->model_catalog_product->get_Event_General($this->request->get['product_id']);

        $data['heading_title'] = $this->language->get('heading_title');

        $data['latlng'] = $product_info['location'];

        $data['text_date_available'] = $this->language->get('text_date_available');
        $data['text_address'] = $this->language->get('text_address');

        $data['language_code'] = $this->config->get('config_language_directory');


        $sdate = substr($product_info['start_date'],0,10);
        $stime = substr($product_info['start_date'],11,5);
        $edate = substr( $product_info['end_date'],0,10);
        $etime = substr($product_info['end_date'],11,5) ;

        $data['start_date'] =  $sdate;
        $data['start_time'] =  $stime;
        $data['end_time'] =  $etime;

        if($sdate==$edate){
            $data['end_date'] = '' ;
        }
        else{
            $data['end_date'] = $edate;
        }
         /*
        $data['start_date'] = substr($product_info['start_date'],0,4) . "-" . substr($product_info['start_date'],5,2) . "-". substr($product_info['start_date'],8,2)     ;
        $data['end_date']  = substr($product_info['end_date'],0,4) . "-" . substr($product_info['end_date'],5,2) . "-". substr($product_info['end_date'],8,2)     ;
        //$data['start_date'] =\DateTime::createFromFormat('m-d-Y', $product_info['start_date'])->format('Y-m-d');

        if ($data['start_date'] = $data['start_date']){

        }
            $data['end_date'] = $product_info['end_date'];
        */
        $data['zoom'] = 17;


		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/googlemap.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/googlemap.tpl', $data);
		} else {
			return $this->load->view('default/template/module/googlemap.tpl', $data);
		}
	}
}
