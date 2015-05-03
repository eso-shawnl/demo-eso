<?php
class ControllerCatalogEventOrganization extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/event_organization');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/event');

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/event');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/event');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_catalog_event->edit_event_to_others($this->request->get['event_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			//$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['product_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['text_none'] = $this->language->get('text_none');
		$data['text_minus'] = $this->language->get('text_minus');
		$data['text_default'] = $this->language->get('text_default');

		$data['entry_name'] = $this->language->get('entry_name');

		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_related'] = $this->language->get('entry_related');


		$data['help_category'] = $this->language->get('help_category');
		$data['help_related'] = $this->language->get('help_related');


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
			'href' => $this->url->link('catalog/event', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('catalog/event_organization/edit', 'token=' . $this->session->data['token'] . '&event_id=' . $this->request->get['event_id'] , 'SSL');

		$data['cancel'] = $this->url->link('catalog/event', 'token=' . $this->session->data['token'] , 'SSL');

		if (isset($this->request->get['event_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$event_info = $this->model_catalog_event->get_event_genaral($this->request->get['event_id']);
		}

		$data['token'] = $this->session->data['token'];

        //manufacturer
		$this->load->model('catalog/manufacturer');

		if (isset($this->request->post['event_manufacturers'])) {
			$manufacturers = $this->request->post['event_manufacturers'];
		} elseif (isset($this->request->get['event_id'])) {
            $manufacturers= $this->model_catalog_event->get_event_manufacturers($this->request->get['event_id']);
		} else {
            $manufacturers = array();
		}

        $data['event_manufacturers'] = array();

        foreach ($manufacturers as $manufacturer_id) {
            $manufacturer_info = $this->model_catalog_manufacturer->get_manufacturer_general($manufacturer_id,1);
            $data['event_manufacturers'][] = array(
                'manufacturer_id' => $manufacturer_info['manufacturer_id'],
                'name'       => $manufacturer_info['name']
            );
        }

		// Categories
		$this->load->model('catalog/category');

		if (isset($this->request->post['event_category'])) {
			$categories = $this->request->post['event_category'];
		} elseif (isset($this->request->get['event_id'])) {
			$categories = $this->model_catalog_event->get_event_categories($this->request->get['event_id']);
		} else {
			$categories = array();
		}

		$data['event_categories'] = array();

		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$data['event_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
				);
			}
		}

        //store
        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        if (isset($this->request->post['event_store'])) {
            $data['event_store'] = $this->request->post['event_store'];
        } elseif (isset($this->request->get['event_id'])) {
            $data['event_store'] = $this->model_catalog_event->get_event_stores($this->request->get['event_id']);
        } else {
            $data['event_store'] = array(0);
        }

        //related
        if (isset($this->request->post['event_related'])) {
            $events = $this->request->post['event_related'];
        } elseif (isset($this->request->get['event_id'])) {
            $events = $this->model_catalog_event->get_event_related($this->request->get['event_id']);
        } else {
            $events = array();
        }

        $data['event_related'] = array();

        foreach ($events as $event_id) {
            $related_info = $this->model_catalog_event->get_event_genaral($event_id);

            if ($related_info) {
                $data['event_related'][] = array(
                    'event_id' => $related_info['event_id'],
                    'name'       => $related_info['name']
                );
            }
        }

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/event_organization.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/event_organization')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

}
