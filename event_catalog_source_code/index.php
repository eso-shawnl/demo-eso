<?php
// version
define('version', '2.0.1.1');

// configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// install
if (!defined('DIR_APPLICATION')) {
	header('location: install/index.php');
	exit;
}

// vQmod
  require_once('./vqmod/vqmod.php');
  VQMod::bootup();
  
// startup
require_once(VQMod::modCheck(DIR_SYSTEM . 'startup.php'));

// registry
$registry = new registry();

// loader
$loader = new loader($registry);
$registry->set('load', $loader);

// config
$config = new config();
$registry->set('config', $config);

// database
$db = new db(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// store
if (isset($_server['https']) && (($_server['https'] == 'on') || ($_server['https'] == '1'))) {
	$store_query = $db->query("select * from " . DB_PREFIX . "store where replace(`ssl`, 'www.', '') = '" . $db->escape('https://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
} else {
	$store_query = $db->query("select * from " . DB_PREFIX . "store where replace(`url`, 'www.', '') = '" . $db->escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
}

if ($store_query->num_rows) {
	$config->set('config_store_id', $store_query->row['store_id']);
} else {
	$config->set('config_store_id', 0);
}

// settings
$query = $db->query("select * from `" . DB_PREFIX . "setting` where store_id = '0' or store_id = '" . (int)$config->get('config_store_id') . "' order by store_id asc");

foreach ($query->rows as $result) {
	if (!$result['serialized']) {
		$config->set($result['key'], $result['value']);
	} else {
		$config->set($result['key'], unserialize($result['value']));
	}
}

if (!$store_query->num_rows) {
	$config->set('config_url', HTTP_SERVER);
	$config->set('config_ssl', HTTPS_SERVER);
}

// url
$url = new url($config->get('config_url'), $config->get('config_secure') ? $config->get('config_ssl') : $config->get('config_url'));
$registry->set('url', $url);

// log
$log = new log($config->get('config_error_filename'));
$registry->set('log', $log);

function error_handler($errno, $errstr, $errfile, $errline) {
	global $log, $config;

	// error suppressed with @
	if (error_reporting() === 0) {
		return false;
	}

    switch ($errno) {
        case E_NOTICE:
        case E_USER_NOTICE:
            $error = 'Notice';
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $error = 'Warning';
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $error = 'Fatal Error';
            break;
        default:
            $error = 'Unknown';
            break;
    }

	if ($config->get('config_error_display')) {
		echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
	}

	if ($config->get('config_error_log')) {
		$log->write('php ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}

	return true;
}

// error handler
set_error_handler('error_handler');

// request
$request = new request();
$registry->set('request', $request);

// response
$response = new response();
$response->addheader('content-type: text/html; charset=utf-8');
$response->setcompression($config->get('config_compression'));
$registry->set('response', $response);

// cache
$cache = new cache('file');
$registry->set('cache', $cache);

// session
$session = new session();
$registry->set('session', $session);

// language detection
$languages = array();

$query = $db->query("select * from `" . DB_PREFIX . "language` where status = '1'");

foreach ($query->rows as $result) {
	$languages[$result['code']] = $result;
}

$detect = '';

if (isset($request->server['HTTP_ACCEPT_LANGUAGE']) && $request->server['HTTP_ACCEPT_LANGUAGE']) {
	$browser_languages = explode(',', $request->server['HTTP_ACCEPT_LANGUAGE']);

	foreach ($browser_languages as $browser_language) {
		foreach ($languages as $key => $value) {
			if ($value['status']) {
				$locale = explode(',', $value['locale']);

				if (in_array($browser_language, $locale)) {
					$detect = $key;

					break 2;
				}
			}
		}
	}
}

if (isset($session->data['language']) && array_key_exists($session->data['language'], $languages) && $languages[$session->data['language']]['status']) {
	$code = $session->data['language'];
} elseif (isset($request->cookie['language']) && array_key_exists($request->cookie['language'], $languages) && $languages[$request->cookie['language']]['status']) {
	$code = $request->cookie['language'];
} elseif ($detect) {
	$code = $detect;
} else {
	$code = $config->get('config_language');
}

if (!isset($session->data['language']) || $session->data['language'] != $code) {
	$session->data['language'] = $code;
}

if (!isset($request->cookie['language']) || $request->cookie['language'] != $code) {
	setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $request->server['HTTP_HOST']);
}

$config->set('config_language_id', $languages[$code]['language_id']);
$config->set('config_language', $languages[$code]['code']);
$config->set('config_language_directory', $languages[$code]['directory']);

// language
$language = new language($languages[$code]['directory']);

$language->load('default');
$registry->set('language', $language);

// document
$registry->set('document', new document());

// customer
$customer = new customer($registry);
$registry->set('customer', $customer);

// customer group
if ($customer->islogged()) {
	$config->set('config_customer_group_id', $customer->getgroupid());
} elseif (isset($session->data['customer']) && isset($session->data['customer']['customer_group_id'])) {
	// for api calls
	$config->set('config_customer_group_id', $session->data['customer']['customer_group_id']);
} elseif (isset($session->data['guest']) && isset($session->data['guest']['customer_group_id'])) {
	$config->set('config_customer_group_id', $session->data['guest']['customer_group_id']);
}

// tracking code
if (isset($request->get['tracking'])) {
	setcookie('tracking', $request->get['tracking'], time() + 3600 * 24 * 1000, '/');

	$db->query("update `" . DB_PREFIX . "marketing` set clicks = (clicks + 1) where code = '" . $db->escape($request->get['tracking']) . "'");
}

// affiliate
$registry->set('affiliate', new affiliate($registry));

// currency
$registry->set('currency', new currency($registry));

// tax
$registry->set('tax', new tax($registry));

// weight
$registry->set('weight', new weight($registry));

// length
$registry->set('length', new length($registry));

// cart
$registry->set('cart', new cart($registry));

// encryption
$registry->set('encryption', new encryption($config->get('config_encryption')));

//openbay pro
$registry->set('openbay', new openbay($registry));

// event
$event = new event($registry);
$registry->set('event', $event);

$query = $db->query("select * from " . DB_PREFIX . "event");

foreach ($query->rows as $result) {
	$event->register($result['trigger'], $result['action']);
}

// front controller
$controller = new front($registry);

// maintenance mode
$controller->addpreaction(new action('common/maintenance'));

// seo url's
$controller->addpreaction(new action('common/seo_url'));

// router

if (isset($request->server['QUERY_STRING']) && !empty($request->server['QUERY_STRING'])) {

    foreach(Url_Decode($request->server['QUERY_STRING']) as $key =>$value){
        $request->get[$key]=$value;
        //$request->request[$key]=$value;
    }

    $action = new Action($request->get['route']);
} else {
    $url_this=getSubdomain($_SERVER['HTTP_HOST'], HTTP_SERVER);

    $result_query=$db->query("select * from tb_events_to_link where name ='".$url_this."'");

    if(!$result_query->num_rows){
        // Action
        $action = new Action('common/home');
    }
    else {

        foreach(json_decode($result_query->row['route']) as $key =>$value){
            $request->get[$key]=$value;
            //$request->request[$key]=$value;
        }

        $request->get['subdomain']=1;

        foreach(json_decode($result_query->row['args']) as $key =>$value){
            $request->get[$key]=$value;
            //$request->request[$key]=$value;
        }

        $action = new Action($request->get['route']);
    }
}
//print_r($request);
// dispatch
$controller->dispatch($action, new action('error/not_found'));

// output
$response->output();
