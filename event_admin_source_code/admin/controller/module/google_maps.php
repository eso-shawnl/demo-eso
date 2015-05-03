<?php
class ControllerModuleGoogleMaps extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/google_maps');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('google_maps', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['gmaps_version'] = $this->language->get('gmaps_version');
		$data['gmaps_info'] = $this->language->get('gmaps_info');
		
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_content_top'] = $this->language->get('text_content_top');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_column_right'] = $this->language->get('text_column_right');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_caution'] = $this->language->get('text_caution');
		
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_settigns'] = $this->language->get('entry_settigns');
		$data['entry_ballon_text'] = $this->language->get('entry_ballon_text');
		$data['entry_theme_box'] = $this->language->get('entry_theme_box');
		$data['entry_theme_box_title'] = $this->language->get('entry_theme_box_title');
		$data['entry_theme_show_box'] = $this->language->get('entry_theme_show_box');
		$data['entry_options'] = $this->language->get('entry_options');
		$data['entry_latlong'] = $this->language->get('entry_latlong');
		$data['entry_widthheight'] = $this->language->get('entry_widthheight');
		$data['entry_zoom'] = $this->language->get('entry_zoom');
		$data['entry_mts'] = $this->language->get('entry_mts');
		$data['entry_mapid'] = $this->language->get('entry_mapid');
		$data['entry_maptype'] = $this->language->get('entry_maptype');
		
		$data['confirm_mapid'] = $this->language->get('confirm_mapid');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_module'] = $this->language->get('button_add_module');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_addmap'] = $this->language->get('button_addmap');
		
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/google_maps', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('module/google_maps', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['modules'] = array();
		
		if (isset($this->request->post['google_maps_module'])) {
			$data['modules'] = $this->request->post['google_maps_module'];
		} elseif ($this->config->get('google_maps_module')) { 
			$data['modules'] = $this->config->get('google_maps_module');
		}
		
		$data['gmaps'] = array();
		
		if (isset($this->request->post['google_maps_module_map'])) {
			$data['gmaps'] = $this->request->post['google_maps_module_map'];
		} elseif ($this->config->get('google_maps_module_map')) { 
			$data['gmaps'] = $this->config->get('google_maps_module_map');
		} 		
					
		$this->load->model('design/layout');
		
		$data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->load->model('tool/image');
		$data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		$data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/google_maps.tpl', $data));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/google_maps')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		
		if (isset($this->request->post['google_maps_module_map'])) {
			foreach ($this->request->post['google_maps_module_map'] as $key => $value) {
				if (!$value['mapalias']) {
					$this->error['warning'] = $this->language->get('error_mapid');
				}			
			}
		}
		
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>