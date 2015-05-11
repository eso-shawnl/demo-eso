<?php
class ModelLocalisationIP extends Model {

	public function addIP($ip,$detail) {

		$this->db->query("call addIPAddress('$ip','$detail',@result,@reason)");

		$_result=get_object_vars($this->db->query("SELECT @result"));
											
		$_reason=get_object_vars($this->db->query("SELECT @reason"));

		$result=array('result'=>$_result['row']['@result'],'reason'=>$_reason['row']['@reason']);

		return $result;						
	}


    public function addIPToSession($ip) {

        $session_id=$this->session->getId();

        $_http_user_agent=$this->request->server['HTTP_USER_AGENT'];

        $this->db->query("call addIPToSession('$ip','$session_id','$_http_user_agent',@result,@reason)");

        $_result=get_object_vars($this->db->query("SELECT @result"));

        $_reason=get_object_vars($this->db->query("SELECT @reason"));

        $result=array('result'=>$_result['row']['@result'],'reason'=>$_reason['row']['@reason']);

        return $result;
    }


    public function addSessionToCustomer() {

        $session_id=$this->session->getId();

        if(isset($this->session->data['customer_id']) && !empty($this->session->data['customer_id'])){

            $customer_id=$this->session->data['customer_id'];
        }
        else{
            $customer_id=0;
        }

        //print_r($this->request);

        $language_code = $this->session->data['language'];




        $request_uri_organic = '';
        $http_host = '';
        $http_referer_organic='';
        if(isset($this->request->server['REQUEST_URI'])
                && !empty($this->request->server['REQUEST_URI'])
                && $this->request->server['REQUEST_URI'] != '/') {

            $request_uri_organic = $this->request->server['REQUEST_URI'];

            if (strpos($this->request->server['REQUEST_URI'], "&amp")) {

                $REQUEST_URI_str = str_replace("&amp;", "&", $this->request->server['REQUEST_URI']);

                $REQUEST_URI_array = explode('&', $REQUEST_URI_str);

                try {
                    $uri = str_replace("&amp;", "&", secret2string(after('index.php?', $REQUEST_URI_array[0])));
                    $uri1 = after('&',$REQUEST_URI_str);
                } catch (Exception $e) {
                    $uri = 'REQUEST_URI is unsecret string';
                    $uri1 ='';
                }
            } else {
                $uri = str_replace("&amp;", "&", secret2string(after('index.php?', $this->request->server['REQUEST_URI'])));
                $uri1 ='';
            }
        }
        else if(isset($this->request->server['HTTP_HOST']) && !empty($this->request->server['HTTP_HOST'])){
            $http_host = $this->request->server['HTTP_HOST'];

            $result_query=$this->db->query("select * from tb_events_to_link where name ='".$this->request->server['HTTP_HOST']."'");

            if(!$result_query->num_rows){
                $uri = $this->request->server['HTTP_HOST'];
                $uri1 ='';
            }
            else {

                foreach(json_decode($result_query->row['route']) as $key =>$value){
                    $route=$key.'='.$value;
                }

                $arg='';
                foreach(json_decode($result_query->row['args']) as $key =>$value){
                    $arg .=$key.'='.$value;
                }
                $uri = $route.'&'.$arg;
                $uri1 ='';

            }
        } else {
            $uri='';
            $uri1 ='';
        }


        if(isset($this->request->server['HTTP_REFERER']) && !empty($this->request->server['HTTP_REFERER'])){
            $http_referer_organic = $this->request->server['HTTP_REFERER'];

            if(strpos($this->request->server['HTTP_REFERER'],"&amp")){

                $HTTP_REFERER_str = str_replace("&amp;", "&", $this->request->server['HTTP_REFERER']);

                $HTTP_REFERER_url=explode('&',$HTTP_REFERER_str);

                try {
                    $referer = str_replace("&amp;", "&", secret2string(after('index.php?',$HTTP_REFERER_url[0])));
                    $referer1 = after('&',$HTTP_REFERER_str);

                } catch (Exception $e) {
                    $referer = 'http_referer is unsecret string';
                    $referer1 = '';
                }

            }
            else{
                $referer = str_replace("&amp;", "&", secret2string(after('index.php?',$this->request->server['REQUEST_URI'])));
                $referer1 ='';
            }
        }
        else {
            $referer='';
            $referer1 ='';
        }

        /*
        if(!empty($this->request->server['HTTP_REFERER'])){
            $http_referer = secret2string(after('index.php?',$this->request->server['HTTP_REFERER']));
        }
        else {
            $http_referer='';
        }
         */

        $this->db->query("call addSessionToCustomer('$session_id',$customer_id,'$language_code','$uri','$uri1','$referer','$referer1','$request_uri_organic','$http_referer_organic','$http_host',@result,@reason)");

        $_result=get_object_vars($this->db->query("SELECT @result"));

        $_reason=get_object_vars($this->db->query("SELECT @reason"));

        $result=array('result'=>$_result['row']['@result'],'reason'=>$_reason['row']['@reason']);

        return $result;
    }

}
