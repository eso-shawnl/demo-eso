<?php
class ControllerCatalogEventImage extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/event_image');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/event');

		$this->getForm();
	}

	public function edit() {
		//$this->load->language('catalog/event_image');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/event');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $this->model_catalog_event->edit_event_image($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
        }

		$this->getForm();
	}

	protected function getForm() {

		$data['heading_title']      = $this->language->get('heading_title');
		
		$data['text_form']          = !isset($this->request->get['event_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['entry_image']        = $this->language->get('entry_image');
        $data['entry_sort_order']   = $this->language->get('entry_sort_order');

		$data['button_save']        = $this->language->get('button_save');
		$data['button_cancel']      = $this->language->get('button_cancel');
        $data['button_remove']      = $this->language->get('button_remove');
		$data['button_image_add']   = $this->language->get('button_image_add');


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

        $data['action'] = $this->url->link('catalog/event_image/edit', 'token=' . $this->session->data['token'] . '&event_id=' . $this->request->get['event_id'] , 'SSL');

        $data['cancel'] = $this->url->link('catalog/event', 'token=' . $this->session->data['token'] , 'SSL');

        $data['token'] = $this->session->data['token'];

        $data['event_id'] = $this->request->get['event_id'];

		// Images

        $event_images = $this->model_catalog_event->get_event_image($this->request->get['event_id']);

		$data['event_images'] = array();

        $this->load->model('tool/image');

		foreach ($event_images as $event_image) {

			if (is_file(DIR_IMAGE . $event_image['image'])) {
				$image = $event_image['image'];
				$thumb = $event_image['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['event_images'][] = array(
                'event_image_id'   => $event_image['event_image_id'],
				'image'             => $image,
				'thumb'             => $this->model_tool_image->resize($thumb, 100, 100),
				'sort_order'        => $event_image['sort_order']
			);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/event_image.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('catalog/product');
			$this->load->model('catalog/option');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach ($results as $result) {
				$option_data = array();

				$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);

				foreach ($product_options as $product_option) {
					$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

					if ($option_info) {
						$product_option_value_data = array();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

							if ($option_value_info) {
								$product_option_value_data[] = array(
									'product_option_value_id' => $product_option_value['product_option_value_id'],
									'option_value_id'         => $product_option_value['option_value_id'],
									'name'                    => $option_value_info['name'],
									'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
									'price_prefix'            => $product_option_value['price_prefix']
								);
							}
						}

						$option_data[] = array(
							'product_option_id'    => $product_option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $product_option['option_id'],
							'name'                 => $option_info['name'],
							'type'                 => $option_info['type'],
							'value'                => $product_option['value'],
							'required'             => $product_option['required']
						);
					}
				}

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'model'      => $result['model'],
					'option'     => $option_data,
					'price'      => $result['price']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
