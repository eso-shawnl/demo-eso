<?php
class ControllerCatalogEventDescription extends Controller {
	private $error = array();

	public function index() {

		$this->load->language('catalog/event_description');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/event');

		$this->getForm();
	}

	public function edit() {

		//$this->load->language('catalog/event_description');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/event');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_catalog_event->edit_event_description($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

		}

		$this->getForm();
	}

	protected function getForm() {

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['event_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
        $data['entry_address'] = $this->language->get('entry_address');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_country'] = $this->language->get('entry_country');

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

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = array();
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

        $data['action'] = $this->url->link('catalog/event_description/edit', 'token=' . $this->session->data['token'] . '&event_id=' . $this->request->get['event_id'] , 'SSL');

        $data['cancel'] = $this->url->link('catalog/event', 'token=' . $this->session->data['token'] , 'SSL');

        $data['token'] = $this->session->data['token'];

        $data['event_id'] = $this->request->get['event_id'];

        if (isset($this->request->post['event_description'])) {

            $data['event_description'] = $this->request->post['event_description'];

        } elseif (isset($this->request->get['event_id'])) {

            $data['event_description'] = $this->model_catalog_event->get_event_description($this->request->get['event_id']);

        } else {

            $data['event_description'] = array();
        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/event_description.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/event')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['event_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}

			if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 5000)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/event')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/event')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

}
