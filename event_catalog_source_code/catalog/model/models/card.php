<?php
/**
 * Created by PhpStorm.
 * User: steven
 * Date: 15/5/12
 * Time: 21:00
 */

class Card extends Model {


    public function check_card_BYIDAndNumber($user,$data = array() ) {

        $event_id               = $data['event_id'];
        $card_id                = $data['card_id'];
        $promotion_code         = $data['promotion_code'];

        return $this->db->query("CALL checkEventTicketByEIDAndTID($event_id,$card_id,$promotion_code,@result)");
    }

}
