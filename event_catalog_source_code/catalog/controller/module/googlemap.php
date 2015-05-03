<?php
class ControllerModuleGoogleMap extends Controller {
	public function index() {
		$this->load->language('module/googlemap');

        $data=array();

        $this->load->model('catalog/product');

        $product_info = $this->model_catalog_product->get_Event_General($this->request->get['product_id']);

        $data['heading_title'] = $this->language->get('heading_title');

        $data['latlng'] = $product_info['location'];

        $data['date_available'] = $this->language->get('date_available');

        $data['language_code'] = $this->config->get('config_language_directory');

        $data['date'] = $product_info['end_date'];

        $data['zoom'] = 17;


		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/googlemap.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/googlemap.tpl', $data);
		} else {
			return $this->load->view('default/template/module/googlemap.tpl', $data);
		}
	}
}
