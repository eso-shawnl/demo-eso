<?php
class Ticket extends Model {

	public function get_ticket_list($user,$data = array()) {

        $result=array();

        if(isset($data) && !empty($data)){

            $filter_name        = $this->db->escape(isset($data['filter_name']) ? $data['filter_name'] : null);
            $filter_status      = $this->db->escape(isset($data['filter_status']) ? $data['filter_status'] : 1);
            $order              = $this->db->escape(isset($data['order']) ? $data['order'] : 'ASC');
            $start              = $this->db->escape(isset($data['start']) ? $data['start'] : 0);
            $limit              = $this->db->escape(isset($data['limit']) ? $data['limit'] : 20);

            $result = $this->db->multi_query("CALL get_tickets('$filter_name',$filter_status,'$order',$start,$limit)");

        }
        else{

        }
        return $result;
	}

	public function get_ticket_price($user,$ticket_id) {
        $result=array();

        if($ticket_id>0){

            $result = $this->db->multi_query("CALL get_ticket_price($ticket_id)");

        }
        else{

        }
        return $result;
	}

    public function get_ticket_discount($user,$ticket_id) {
        $result=array();

        if($ticket_id>0){

            $result = $this->db->multi_query("CALL get_ticket_discount($ticket_id)");

        }
        else{

        }
        return $result;
    }

	public function get_ticket_general($user,$ticket_id) {
        $result=array();

        if($ticket_id > 0){

            $result = $this->db->multi_query("CALL get_ticket_general($ticket_id)");

        }
        else {

        }
        if(!empty($result)){
            return $result[0];
        }
        else{
            return $result;
        }

	}

	public function get_ticket_total($user,$data = array()) {

        if(isset($data) && !empty($data)){
            $filter_name        = $this->db->escape(isset($data['filter_name']) ? $data['filter_name'] : null);
            $filter_status      = $this->db->escape(isset($data['filter_status']) ? $data['filter_status'] : 1);
            $order              = $this->db->escape(isset($data['order']) ? $data['order'] : 'ASC');
            $start              = $this->db->escape(isset($data['start']) ? $data['start'] : 0);
            $limit              = $this->db->escape(isset($data['limit']) ? $data['limit'] : 20);

            $this->db->multi_query("call get_total_tickets('$filter_name',$filter_status,'$order',$start,$limit,@result)");
            $_result=get_object_vars($this->db->query("SELECT @result"));

            return $_result['row']['@result'];

        }
        else{

        }

	}

    public function get_ticket_to_event($user,$ticket_id) {

        $result=array();

        if($ticket_id > 0){

            $query = $this->db->multi_query("CALL get_ticket_to_event($ticket_id)");

        }
        else {

        }

        if(!empty($query)){
            $result =  $query[0];

        }

        return $result;

    }

    public function get_ticket_for_cart($user,$data = array()) {

        $result=array();

        $event_id= $data['event_id'];

        $ticket_id =51;

        if($event_id > 0){

            $query = $this->db->multi_query("CALL get_ticket_price_for_cart($event_id,$ticket_id)");

        }
        else {

        }
        if(!empty($query)){
            $result['ticket_price_list'] =$query;
        }
        else{
            $result['ticket_price_list'] =null;
        }

        if($ticket_id > 0){

            $query = $this->db->multi_query("CALL get_ticket_position_for_cart($ticket_id)");

        }
        else {

        }
        if(!empty($query)){
            $result['ticket_position_list'] =$query;
        }
        else{
            $result['ticket_position_list'] =null;
        }

        return $result;

    }

}
