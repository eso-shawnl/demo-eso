<?php
class Url {
	private $domain;
	private $ssl;
	private $rewrite = array();

	public function __construct($domain, $ssl = '') {
		$this->domain = $domain;
		$this->ssl = $ssl;
	}

	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}

	public function link($route, $args = '', $secure = false) {
		if (!$secure) {
			$url = $this->domain;
		} else {
			$url = $this->ssl;
		}

		$url .= 'index.php?';

		if ($args) {
			$args = str_replace('&', '&amp;', '&' . ltrim($args, '&'));
		}

        $url .= Url_Encode('route=' . $route.$args);
		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}

		return  $url;
	}
}
