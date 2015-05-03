<?php
class ControllerCatalogManufacturerDescription extends Controller {
	private $error = array();

	public function index() {

		$this->load->language('catalog/manufacturer_description');

		$this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/manufacturer');

		$this->getForm();
	}

	public function edit() {

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/manufacturer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_catalog_manufacturer->edit_manufacturer_description($this->request->get['manufacturer_id'],$this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

            //$this->response->redirect($this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] , 'SSL'));
		}

		$this->getForm();
	}

	protected function getForm() {

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['event_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_description'] = $this->language->get('entry_description');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
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

        $data['action'] = $this->url->link('catalog/manufacturer_description/edit', 'token=' . $this->session->data['token'] . '&manufacturer_id=' . $this->request->get['manufacturer_id'] , 'SSL');

        $data['cancel'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'] , 'SSL');

        $data['token'] = $this->session->data['token'];

        $data['manufacturer_id'] = $this->request->get['manufacturer_id'];

        if (isset($this->request->post['manufacturer_description'])) {

            $data['manufacturer_description'] = $this->request->post['manufacturer_description'];

        } elseif (isset($this->request->get['manufacturer_id'])) {

            $data['manufacturer_description'] = $this->model_catalog_manufacturer->get_manufacturer_description($this->request->get['manufacturer_id']);

        } else {

            $data['manufacturer_description'] = array();
        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/manufacturer_description.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['manufacturer_description'] as $language_id => $value) {

			if ((utf8_strlen($value['description']) < 3) || (utf8_strlen($value['description']) > 5000)) {
				$this->error['description'][$language_id] = $this->language->get('error_meta_title');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/manufacturer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

}
