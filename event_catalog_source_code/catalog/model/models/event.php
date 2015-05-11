<?php
class Event extends Model {

    public function get_event_genaral($user,$event_id) {

        $result=array();

        if($event_id > 0){

            $result = $this->db->multi_query("CALL get_event_genaral($event_id)");
        }
        else {

        }

        return $result;
    }


    public function check_Event_TicketByEIDAndTID($user,$data = array() ) {

        $event_id       = isset($data['event_id']) ?    $data['event_id'] :0;
        $ticket_id      = isset($data['product_id']) ?  $data['product_id'] :0;

        return $this->db->multi_query("CALL checkEventTicketByEIDAndTID($event_id,$ticket_id)");
    }

}
