<?php
class ControllerTicketSuccess extends Controller {
	public function index() {
		$this->load->language('account/success');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_message'] = sprintf($this->language->get('text_message'), $this->url->link('information/contact'));

		$data['button_continue'] = $this->language->get('button_continue');

		$data['content_top'] = '';

		$data['continue'] = $this->url->link('product/product','product_id=50');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/ticket/success.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/ticket/success.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/ticket/success.tpl', $data));
		}
	}
}