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

        $language_code = $this->session->data['language'];

        $request_url = secret2string(after('index.php?',$this->request->server['REQUEST_URI']));

        if(!empty($this->request->server['HTTP_REFERER'])){
            $http_referer = secret2string(after('index.php?',$this->request->server['HTTP_REFERER']));
        }
        else {
            $http_referer='';
        }

        $this->db->query("call addSessionToCustomer('$session_id',$customer_id,'$language_code','$request_url','$http_referer',@result,@reason)");

        $_result=get_object_vars($this->db->query("SELECT @result"));

        $_reason=get_object_vars($this->db->query("SELECT @reason"));

        $result=array('result'=>$_result['row']['@result'],'reason'=>$_reason['row']['@reason']);

        return $result;
    }

}
