<?php
class ControllerModuleOrganization extends Controller {
	public function index($setting) {
        $this->load->language('module/organization');

        $data['heading_title'] = $this->language->get('heading_title');

        if ($this->request->server['HTTPS']) {
            $server = $this->config->get('config_ssl');
        } else {
            $server = $this->config->get('config_url');
        }

        $this->load->model('catalog/product');

        $this->load->model('catalog/manufacturer');

        $manufacturers= $this->model_catalog_product->get_event_manufacturers($this->request->get['product_id']);

        foreach ($manufacturers as $manufacturer_id) {
            $manufacturer_info = $this->model_catalog_manufacturer->get_manufacturer_general($manufacturer_id,1);
            $manufacturer_description = $this->model_catalog_manufacturer->get_manufacturer_description($manufacturer_id);
            $data['event_manufacturers'][] = array(
                'logo'         => $data['logo'] = $server . 'image/' . $manufacturer_info['image'],
                'website'      => $manufacturer_info['website'] ,
                'description'  => html_entity_decode($manufacturer_description[$this->config->get('config_language_id')]['description'])
            );
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/organization.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/organization.tpl', $data);
        } else {
            return $this->load->view('default/template/module/organization.tpl', $data);
        }
	}
}
