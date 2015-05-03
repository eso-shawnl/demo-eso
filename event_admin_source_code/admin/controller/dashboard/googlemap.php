<?php
class ControllerDashboardGoogleMap extends Controller {
	public function index() {
		$this->load->language('dashboard/googlemap');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');


        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data=array();
        $sql = "select * from tb_ip_info";

        $query = $this->db->query($sql);

        foreach($query->rows as $value){
            $ip = $value['ip'];
            $json = json_decode($value['detail'], true);

            $lat = $json['lat'];
            $lon = $json['lon'];

            $date['location'][]=array('ip'    =>$ip,
                          'lat'   =>$lat,
                          'lon'   =>$lon);

        }
        $data ['token'] = $this->session->data ['token'];

        $data['language_code'] = $this->config->get('config_language_directory');

        $data['zoom'] = 17;

        $data ['header'] = $this->load->controller ( 'common/header' );
        $data ['column_left'] = $this->load->controller ( 'common/column_left' );
        $data ['footer'] = $this->load->controller ( 'common/footer' );

        $this->response->setOutput($this->load->view('dashboard/googlemap.tpl', $data));
	}

    public function autocomplete(){

        $result=array();

        $sql = "select a.ip,a.detail,count(distinct c.customer_id) as num
                from tb_ip_info a,tb_ip_to_session b,tb_session_to_customer c
                where c.session_id = b.session_id and a.ip = b.ip
                group by a.ip,a.detail;";

        $query = $this->db->query($sql);

        foreach($query->rows as $value){

            $ip = $value['ip'];
            $json = json_decode($value['detail'], true);

            $result['location'][]=array('ip'    =>$ip,
                'lat'   =>$json['lat'],
                'lng'   =>$json['lon'],
                'num'   =>$value['num']);

        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($result));
    }
}
