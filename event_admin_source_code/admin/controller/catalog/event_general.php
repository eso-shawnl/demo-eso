<?php
class ControllerCatalogEventGeneral extends Controller {
	private $error = array();

	public function index() {

		$this->load->language('catalog/event_general');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/event');

		$this->getForm();
	}

	public function edit() {

		//$this->load->language('catalog/event_general');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/event');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_catalog_event->edit_event_general($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

		}

		$this->getForm();
	}

	protected function getForm() {

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['event_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        $data['entry_name']         = $this->language->get('entry_name');
        $data['entry_image']        = $this->language->get('entry_image');
        $data['entry_layout']       = $this->language->get('entry_layout');
        $data['entry_publish_date'] = $this->language->get('entry_publish_date');
        $data['entry_start_date']   = $this->language->get('entry_start_date');
        $data['entry_end_date']     = $this->language->get('entry_end_date');
        $data['entry_minimum']      = $this->language->get('entry_minimum');
        $data['entry_maximum']      = $this->language->get('entry_maximum');
        $data['entry_sort_order']   = $this->language->get('entry_sort_order');
        $data['entry_status']       = $this->language->get('entry_status');
        $data['entry_created_date'] = $this->language->get('entry_created_date');
        $data['entry_modified_date'] = $this->language->get('entry_modified_date');
        $data['entry_location']     = $this->language->get('entry_location');


        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_none'] = $this->language->get('text_none');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = array();
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/event', 'token=' . $this->session->data['token'] , 'SSL')
        );


        if (isset($this->request->post['event_id'])) {
            $data['event_id'] = $this->request->post['event_id'];
        } elseif (isset($this->request->get['event_id'])) {
            $data['event_id'] = $this->request->get['event_id'];
        } else {
            $data['event_id'] = 0;
        }

        $data['action'] = $this->url->link('catalog/event_general/edit', 'token=' . $this->session->data['token'] . '&event_id=' . $data['event_id'] , 'SSL');

        $data['cancel'] = $this->url->link('catalog/event', 'token=' . $this->session->data['token'] , 'SSL');

        $data['token'] = $this->session->data['token'];

        $data['event_info'] = $this->model_catalog_event->get_event_genaral($data['event_id']);;

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($data['event_info']) && is_file(DIR_IMAGE . $data['event_info']['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($data['event_info']['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $this->load->model('design/layout');

        $data['layouts'] = $this->model_design_layout->getLayouts();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/event_general.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/event')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();



		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
