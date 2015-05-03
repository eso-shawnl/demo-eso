<?php
class ControllerCommonIp extends Controller {
	
	public function index() {
		$this->load->model('localisation/ip');

        $data['ipaddress']='';

        $data['ip_detail']='';

		if(isset($this->request->get['REMOTE_ADDR'])){

			$data['ipaddress']=$this->request->get['REMOTE_ADDR'];

            $data['ip_detail']=@unserialize(file_get_contents('http://ip-api.com/php/'.$data['ipaddress']));

            //$this->model_localisation_ip->addIP($data['ipaddress'],$data['ip_detail']);
		}
		/*
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/ip.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/ip.tpl', $data);
		} else {
			return $this->load->view('default/template/common/ip.tpl', $data);
		}*/

	}
	/*
	"status": "success",
	"country": "COUNTRY",
	"countryCode": "COUNTRY CODE",
	"region": "REGION CODE",
	"regionName": "REGION NAME",
	"city": "CITY",
	"zip": "ZIP CODE",
	"lat": LATITUDE,
	"lon": LONGITUDE,
	"timezone": "TIME ZONE",
	"isp": "ISP NAME",
	"org": "ORGANIZATION NAME",
	"as": "AS NUMBER / NAME",
	"query": "IP ADDRESS USED FOR QUERY"
	*/
    public function autocomplete($registry){

		$this->load->model('localisation/ip');
		
		$obj=json_decode($_POST['userinfo']);

		$this->model_localisation_ip->addIP($obj->query,$_POST['userinfo']);

        $this->model_localisation_ip->addIPToSession($obj->query);

        //$this->model_Localisation_ip->addSessionToCustomer();
	}
	
}
