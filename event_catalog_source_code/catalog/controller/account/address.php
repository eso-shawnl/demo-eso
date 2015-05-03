<?php
class ControllerAccountAddress extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/address', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/address');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/address');

		$this->getList();
	}

	public function add() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/address', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/address');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/address');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_account_address->addAddress($this->request->post);

			$this->session->data['success'] = $this->language->get('text_add');

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('address_add', $activity_data);

			$this->response->redirect($this->url->link('account/address', '', 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/address', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/address');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/address');
        $this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $address     = $this->model_account_address->edit_address($this->request->post);
            if(isset($this->request->get['address_id']) && !empty($this->request->get['address_id'])){
                $address_old = $this->request->get['address_id'];
            }
            else{
                $address_old=0;
            }

            if(isset($this->request->get['address_type_id']) && !empty($this->request->get['address_type_id'])){
                $address_type_id = $this->request->get['address_type_id'];
            }
            else{
                $address_type_id=1;
            }

            if((isset($address) && $address['result'])){
                $this->model_account_customer->edit_customer_to_address('DELETE',(int)$this->customer->getId(),$address_old,$address_type_id);
                $this->model_account_customer->edit_customer_to_address('INSERT',(int)$this->customer->getId(),$address['reason'],$address_type_id);
            }

			// Default Shipping Address
			if (isset($this->session->data['shipping_address']['address_id']) && ($this->request->get['address_id'] == $this->session->data['shipping_address']['address_id'])) {
				$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->request->get['address_id']);

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}

			// Default Payment Address
			if (isset($this->session->data['payment_address']['address_id']) && ($this->request->get['address_id'] == $this->session->data['payment_address']['address_id'])) {
				$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->request->get['address_id']);

				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			}

			$this->session->data['success'] = $this->language->get('text_edit');

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('address_edit', $activity_data);

			$this->response->redirect($this->url->link('account/address', '', 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/address', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/address');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/address');

		if (isset($this->request->get['address_id']) && $this->validateDelete()) {
			$this->model_account_address->deleteAddress($this->request->get['address_id']);

			// Default Shipping Address
			if (isset($this->session->data['shipping_address']['address_id']) && ($this->request->get['address_id'] == $this->session->data['shipping_address']['address_id'])) {
				unset($this->session->data['shipping_address']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}

			// Default Payment Address
			if (isset($this->session->data['payment_address']['address_id']) && ($this->request->get['address_id'] == $this->session->data['payment_address']['address_id'])) {
				unset($this->session->data['payment_address']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			}

			$this->session->data['success'] = $this->language->get('text_delete');

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('address_delete', $activity_data);

			$this->response->redirect($this->url->link('account/address', '', 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/address', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_address_book'] = $this->language->get('text_address_book');
		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_new_address'] = $this->language->get('button_new_address');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['addresses'] = array();

        $this->load->model('account/customer');

		$address_list = $this->model_account_customer->get_customer_to_address((int)$this->customer->getId());

        $data['address_types'] = $this->model_account_address->get_address_type();

        foreach($data['address_types'] as $key => $value){
            $data['address_types'][$key]['language'] = $this->language->get($value['type_description']);
            $data['add'][$value['type_description']] = $this->url->link('account/address/add', 'address_type_id=' . $value['address_type_id'], 'SSL');
        }

		foreach ($address_list as $result) {

            $addresses = $this->model_account_address->get_address((int)$result['address_id']);

            $format = '{full_address}' . "\n" . '{street_number}' . "\n" . '{route}' . "\n" . '{suburb}' . "\n" .  '{city}'. "\n" . ' {postcode}' . "\n" . '{state}' . "\n" . '{country}';
            foreach ($addresses as $address) {
                $find = array(
                    '{full_address}',
                    '{street_number}',
                    '{route}',
                    '{suburb}',
                    '{city}',
                    '{postcode}',
                    '{state}',
                    '{country}'
                );

                $replace = array(
                    'full_address'  => $address['full_address'],
                    'street_number' => $address['street_number'],
                    'route'         => $address['route'],
                    'suburb'        => $address['suburb'],
                    'city'          => $address['city'],
                    'postcode'      => $address['postcode'],
                    'state'         => $address['state'],
                    'country'       => $address['country']
                );

                $data['addresses'][(int)$result['address_type_id']][] = array(
                    'address_id'    => $result['address_id'],
                    'address'       => str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))),
                    'update'        => $this->url->link('account/address/edit', 'address_id=' . $result['address_id'], 'SSL'),
                    'delete'        => $this->url->link('account/address/delete', 'address_id=' . $result['address_id'], 'SSL')
                );
            }
		}

		$data['back'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/address_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/address_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/address_list.tpl', $data));
		}
	}

	protected function getForm() {
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/address', '', 'SSL')
		);

		if (!isset($this->request->get['address_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_edit_address'),
				'href' => $this->url->link('account/address/add', '', 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_edit_address'),
				'href' => $this->url->link('account/address/edit', 'address_id=' . $this->request->get['address_id'], 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit_address'] = $this->language->get('text_edit_address');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_loading'] = $this->language->get('text_loading');

        $data['entry_full_address'] = $this->language->get('entry_full_addresss');
        $data['entry_street_number'] = $this->language->get('entry_street_number');
        $data['entry_route'] = $this->language->get('entry_route');
        $data['entry_postcode'] = $this->language->get('entry_postcode');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['entry_suburb'] = $this->language->get('entry_suburb');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_default'] = $this->language->get('entry_default');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');
		$data['button_upload'] = $this->language->get('button_upload');

        if (isset($this->error['street_number'])) {
            $data['error_street_number'] = $this->error['street_number'];
        } else {
            $data['error_street_number'] = '';
        }

        if (isset($this->error['route'])) {
            $data['error_route'] = $this->error['route'];
        } else {
            $data['error_route'] = '';
        }

        if (isset($this->error['city'])) {
            $data['error_city'] = $this->error['city'];
        } else {
            $data['error_city'] = '';
        }

        if (isset($this->error['suburb'])) {
            $data['error_suburb'] = $this->error['suburb'];
        } else {
            $data['error_suburb'] = '';
        }

        if (isset($this->error['postcode'])) {
            $data['error_postcode'] = $this->error['postcode'];
        } else {
            $data['error_postcode'] = '';
        }

        if (isset($this->error['country'])) {
            $data['error_country'] = $this->error['country'];
        } else {
            $data['error_country'] = '';
        }

        if (isset($this->error['zone'])) {
            $data['error_zone'] = $this->error['zone'];
        } else {
            $data['error_zone'] = '';
        }

		if (isset($this->error['custom_field'])) {
			$data['error_custom_field'] = $this->error['custom_field'];
		} else {
			$data['error_custom_field'] = array();
		}

        $url = '';

        if (isset($this->request->get['address_id'])) {
            $url .= '&address_id=' . $this->request->get['address_id'];
        }

        if (isset($this->request->get['address_type_id'])) {
            $url .= '&address_type_id=' . $this->request->get['address_type_id'];
        }

		$data['action'] = $this->url->link('account/address/edit', $url, 'SSL');

		if (isset($this->request->get['address_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $address_info = $this->model_account_address->get_address($this->request->get['address_id']);

		}


		if (isset($this->request->post['full_address'])) {
			$data['full_address'] = $this->request->post['full_address'];
		} elseif (!empty($address_info)) {
			$data['full_address'] = $address_info[0]['full_address'];
		} else {
			$data['full_address'] = '';
		}

        if (isset($this->request->post['street_number'])) {
            $data['street_number'] = $this->request->post['street_number'];
        } elseif (!empty($address_info)) {
            $data['street_number'] = $address_info[0]['street_number'];
        } else {
            $data['street_number'] = '';
        }

        if (isset($this->request->post['route'])) {
            $data['route'] = $this->request->post['route'];
        } elseif (!empty($address_info)) {
            $data['route'] = $address_info[0]['route'];
        } else {
            $data['route'] = '';
        }

        if (isset($this->request->post['city'])) {
            $data['city'] = $this->request->post['city'];
        } elseif (!empty($address_info)) {
            $data['city'] = $address_info[0]['city'];
        } else {
            $data['city'] = '';
        }

        if (isset($this->request->post['suburb'])) {
            $data['suburb'] = $this->request->post['suburb'];
        } elseif (!empty($address_info)) {
            $data['suburb'] = $address_info[0]['suburb'];
        } else {
            $data['suburb'] = '';
        }
        if (isset($this->request->post['postcode'])) {
            $data['postcode'] = $this->request->post['postcode'];
        } elseif (!empty($address_info)) {
            $data['postcode'] = $address_info[0]['postcode'];
        } else {
            $data['postcode'] = '';
        }

        if (isset($this->request->post['country'])) {
            $data['country'] = $this->request->post['country'];
        } elseif (!empty($address_info)) {
            $data['country'] = $address_info[0]['country'];
        } else {
            $data['country'] = '';
        }

        if (isset($this->request->post['state'])) {
            $data['zone'] = $this->request->post['state'];
        } elseif (!empty($address_info)) {
            $data['zone'] = $address_info[0]['state'];
        } else {
            $data['zone'] = '';
        }


         $data['custom_fields'] = array();//$this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));
        /*
		if (isset($this->request->post['custom_field'])) {
			$data['address_custom_field'] = $this->request->post['custom_field'];
		} elseif (isset($address_info)) {
			$data['address_custom_field'] = $address_info[0]['custom_field'];
		} else {
			$data['address_custom_field'] = array();
		}

		if (isset($this->request->post['default'])) {
			$data['default'] = $this->request->post['default'];
		} elseif (isset($this->request->get['address_id'])) {
			$data['default'] = $this->customer->getAddressId() == $this->request->get['address_id'];
		} else {
			$data['default'] = false;
         *
         */

        $data['default'] = false;

		$data['back'] = $this->url->link('account/address', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/address_form.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/address_form.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/address_form.tpl', $data));
		}
	}

	protected function validateForm() {
        /*
		if ((utf8_strlen(trim($this->request->post['street_number'])) < 3) || (utf8_strlen(trim($this->request->post['street_number'])) > 128)) {
			$this->error['street_number'] = $this->language->get('error_street_number');
		}
         */

        if ((utf8_strlen(trim($this->request->post['route'])) < 3) || (utf8_strlen(trim($this->request->post['route'])) > 128)) {
            $this->error['route'] = $this->language->get('error_route');
        }

        if ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128)) {
            $this->error['city'] = $this->language->get('error_city');
        }

        if ((utf8_strlen(trim($this->request->post['suburb'])) < 2) || (utf8_strlen(trim($this->request->post['suburb'])) > 128)) {
            $this->error['suburb'] = $this->language->get('error_suburb');
        }

        if ($this->request->post['country'] == '') {
            $this->error['country'] = $this->language->get('error_country');
        }
        /*
        if ($this->request->post['postcode'] == '') {
            $this->error['postcode'] = $this->language->get('error_postcode');
        }
        */
        if (!isset($this->request->post['zone']) || $this->request->post['zone'] == '') {
            $this->error['zone'] = $this->language->get('error_zone');
        }

		return !$this->error;
	}

	protected function validateDelete() {
		if ($this->model_account_address->getTotalAddresses() == 1) {
			$this->error['warning'] = $this->language->get('error_delete');
		}

		if ($this->customer->getAddressId() == $this->request->get['address_id']) {
			$this->error['warning'] = $this->language->get('error_default');
		}

		return !$this->error;
	}
}
