<?php
class ModelCatalogEvent extends Model {

	public function editevent($event_id, $data) {
		$this->event->trigger('pre.admin.event.edit', $data);

		$this->cache->delete('event');

		$this->event->trigger('post.admin.event.edit', $event_id);
	}

	public function deleteevent($event_id) {
		$this->event->trigger('pre.admin.event.delete', $event_id);

		$this->cache->delete('event');

		$this->event->trigger('post.admin.event.delete', $event_id);
	}

	public function get_Events($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "events e WHERE 1=1 ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND e.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND e.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND e.status = '" . (int)$data['filter_status'] . "'";
		}
		
		$sql .= " GROUP BY e.event_id";

		$sort_data = array(
			'e.name',
			'e.model',
			'e.status',
			'e.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY e.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalevents($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.event_id) AS total FROM " . DB_PREFIX . "events p LEFT JOIN " . DB_PREFIX . "events_description pd ON (p.event_id = pd.event_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

    public function edit_event_description($data = array()){

        $this->event->trigger('pre.admin.event_description.edit', $data);

        $result=array();

        if(isset($data['id']) && !empty($data['id'])){

            $event_id=$data['id'];

            $i=0;

            foreach ($data['event_description'] as $key =>$val){

                $name               = $val['name'];
                $description        = $val['description'];
                $meta_title         = $val['meta_title'];
                $meta_description   = $val['meta_description'];
                $meta_keyword       = $val['meta_keyword'];
                $address            = serialize(array('address'=>$val['address'],'city'=>$val['city'],'country'=>$val['country']));

                $this->db->query("call edit_event_description($event_id,$key,'$name','$description','$meta_title','$meta_description','$meta_keyword','$address',@result,@reason)");

                $_result=get_object_vars($this->db->query("SELECT @result"));

                $_reason=get_object_vars($this->db->query("SELECT @reason"));

                $result[$i++]=array('event_id'=>$event_id,'result'=>$_result['row']['@result'],'reason'=>$_reason['row']['@reason']);

            }

            $this->cache->delete('event');

            $this->event->trigger('post.admin.event_description.edit', $event_id);
        }
        else {
            $result['0']=array('result'=>false,'reason'=>'event id is null!');
        }

        return $result;
    }

    public function get_event_description($event_id){
        /*
         *
         Array
            (
                [0] => Array
                (
                    [event_id] => 50
                    [language_id] => 3
                    [name] => sdfsaf
                    [description] => &lt;p&gt;tetet&lt;br&gt;&lt;/p&gt;
                    [meta_title] => &lt;p&gt;sdfsaf&lt;br&gt;&lt;/p&gt;
                    [meta_description] => &lt;p&gt;dgshfjgfhj&lt;br&gt;&lt;/p&gt;
                    [meta_keyword] => rtyryrt
                    [address] => qqqqqq
                    [created_date] =>
                    [modified_date] => 2015-04-03 03:19:48
                    [city] => qqqqqq
                    [country] => qqqqqq
                )
         */
        $result=array();

        if($event_id > 0){

            $query = $this->db->multi_query("CALL get_event_description($event_id)");

            if(!empty($query)){

                foreach ($query as $value) {

                    foreach ($value as $key => $val) {
                        $result[$value['language_id']][$key]=$val;
                    }
                    $address= unserialize($value['address']);
                    $result[$value['language_id']]['address']   = $address['address'];
                    $result[$value['language_id']]['city']      = $address['city'];
                    $result[$value['language_id']]['country']   = $address['country'];

                }
            }
            else {

            }
        }
        else {

        }

        return $result;
    }

    public function get_event_genaral($event_id) {

        $result=array();

        if($event_id > 0){

            $query = $this->db->multi_query("CALL get_event_genaral($event_id)");

            if(!empty($query)){

                foreach ($query as $value) {
                    foreach ($value as $key=>$val) {
                        $result[$key]=$val;
                    }
                }
            }
            else {

            }
        }
        else {

        }

        return $result;
    }

    public function edit_event_general($data = array()){

        $this->event->trigger('pre.admin.event_general.edit', $data);

        $result=array();

        if(isset($data['id']) && !empty($data['id'])){
            $event_id       =$data['id'];
        }
        else {
            $event_id       =0;
        }

        $image          =$data['image'];
        $name           =$data['event_name'];
        $layout         =$data['layout'];
        $location       =$data['location'];
        $publish_date   =$data['publish_date'];
        $start_date     =$data['start_date'];
        $end_date       =$data['end_date'];
        $minimum        =$data['minimum'];
        $maximum        =$data['maximum'];
        $status         =$data['status'];
        $sort_order     =$data['sort_order'];

        $this->db->query("call edit_event_general($event_id,'$image','$name',$layout,'$location','$publish_date',
                                                '$start_date','$end_date',$minimum,$maximum,$status,$sort_order,
                                                @result,@reason)");

        $_result=get_object_vars($this->db->query("SELECT @result"));

        $_reason=get_object_vars($this->db->query("SELECT @reason"));

        $result[]=array('event_id'=>$event_id,'result'=>$_result['row']['@result'],'reason'=>$_reason['row']['@reason']);

        $this->event->trigger('post.admin.event_general.edit', $event_id);


        return $result;
    }

    public function get_event_image($event_id) {
        /*
         Array
            (
                [0] => Array
                    (
                        [event_image_id] => 2480
                        [event_id] => 50
                        [image] => catalog/product/posters_1.jpg
                        [sort_order] => 4
                        [created_date] => 2015-04-03 15:14:53
                        [modified_date] =>
                    )
             )
         *
         */
        $result=array();

        if($event_id > 0){

            $result = $this->db->multi_query("CALL get_event_image($event_id)");

        }
        else {

        }

        return $result;
    }

    public function edit_event_image($data = array()){

        /*
         *
        [id] => 50
        [image] => catalog/product/poster_0.jpg
        [event_image_id] =>0
        [sort_order] => 0
         */

        $this->event->trigger('pre.admin.event_image.edit', $data);

        $result=array();

        if(isset($data['event_id']) && !empty($data['event_id'])){

            $event_id=$data['event_id'];

            $this->db->query("call edit_event_image('DELETE',$event_id,0,'',0,@result,@reason)");

            $_result=get_object_vars($this->db->query("SELECT @result"));

            $_reason=get_object_vars($this->db->query("SELECT @reason"));

            if($_result['row']['@result']){

                foreach ($data['event_image'] as $val){

                    $image          =$val['image'];

                    if(isset($val['event_image_id'])){
                        $event_image_id =$val['event_image_id'];
                    }
                    else{
                        $event_image_id =0;
                    }

                    $sort_order     =$val['sort_order'];

                    $this->db->query("call edit_event_image('UPDATE&INSERT',$event_id,$event_image_id,'$image',$sort_order,
                                                    @result,@reason)");

                    $_result=get_object_vars($this->db->query("SELECT @result"));

                    $_reason=get_object_vars($this->db->query("SELECT @reason"));

                    $result[]=array('event_id'=>$event_id,'result'=>$_result['row']['@result'],'reason'=>$_reason['row']['@reason']);

                    $this->cache->delete('event');

                    $this->event->trigger('post.admin.event_image.edit', $event_id);
                }
            }
            else{
                $result[]=array('result'=>false,'reason'=>'remove image error!'.$_reason['row']['@reason']);
            }

        }
        else {
            $result[]=array('result'=>false,'reason'=>'event id is null!');
        }

        return $result;
    }

    //
    public function get_event_categories($event_id) {

        $event_to_category = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "events_to_category WHERE event_id = '" . (int)$event_id . "'");

        foreach ($query->rows as $result) {
            $event_to_category[] = $result['category_id'];
        }

        return $event_to_category;
    }

    public function get_event_manufacturers($event_id) {
        $event_manufacturer_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "events_to_manufacturer WHERE event_id = '" . (int)$event_id . "'");

        foreach ($query->rows as $result) {
            $event_manufacturer_data[] = $result['manufacturer_id'];
        }

        return $event_manufacturer_data;
    }

    public function get_event_stores($event_id) {
        $event_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "events_to_store WHERE event_id = '" . (int)$event_id . "'");

        foreach ($query->rows as $result) {
            $event_store_data[] = $result['store_id'];
        }

        return $event_store_data;
    }

    public function get_event_related($event_id) {
        $event_related_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "events_related WHERE event_id = '" . (int)$event_id . "'");

        foreach ($query->rows as $result) {
            $event_related_data[] = $result['related_id'];
        }

        return $event_related_data;
    }

    public function get_events_genaral($filter_name,$start,$limit) {

        $result = $this->db->multi_query("CALL get_events_genaral('$filter_name',$start,$limit)");

        return $result;
    }

    public function edit_event_to_others($event_id,$data = array()){

        //event to category
        if (isset($data['event_category'])) {
            $this->event->trigger('pre.admin.event_category.edit', $data);

            $this->db->query("call edit_event_to_category('DELETE',$event_id,0,@result,@reason)");

            foreach ($data['event_category'] as $id) {
                $this->db->query("call edit_event_to_category('INSERT',$event_id,$id,@result,@reason)");
            }
            $this->cache->delete('event');

            $this->event->trigger('post.admin.event_category.edit', $event_id);
        }

        //event to related
        if (isset($data['event_related'])) {
            $this->event->trigger('pre.admin.event_related.edit', $data);

            $this->db->query("call edit_event_related('DELETE',$event_id,0,@result,@reason)");

            foreach ($data['event_related'] as $id) {
                $this->db->query("call edit_event_related('INSERT',$event_id,$id,@result,@reason)");
            }
            $this->cache->delete('event');

            $this->event->trigger('post.admin.event_related.edit', $event_id);
        }

        //event to manufacturer
        if (isset($data['event_manufacturer'])) {
            $this->event->trigger('pre.admin.event_manufacturer.edit', $data);

            $this->db->query("call edit_event_to_manufacturer('DELETE',$event_id,0,@result,@reason)");

            foreach ($data['event_manufacturer'] as $id) {
                $this->db->query("call edit_event_to_manufacturer('INSERT',$event_id,$id,@result,@reason)");
            }
            $this->cache->delete('event');

            $this->event->trigger('post.admin.event_manufacturer.edit', $event_id);
        }

        //event to store
        if (isset($data['event_store'])) {
            $this->event->trigger('pre.admin.event_store.edit', $data);

            $this->db->query("call edit_event_to_store('DELETE',$event_id,0,@result,@reason)");

            foreach ($data['event_store'] as $id) {
                $this->db->query("call edit_event_to_store('INSERT',$event_id,$id,@result,@reason)");
            }
            $this->cache->delete('event');

            $this->event->trigger('post.admin.event_store.edit', $event_id);
        }



    }
}
