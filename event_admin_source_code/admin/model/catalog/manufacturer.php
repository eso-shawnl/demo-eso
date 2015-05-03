<?php
class ModelCatalogManufacturer extends Model {
	public function addManufacturer($data) {
		$this->event->trigger('pre.admin.manufacturer.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "'");

		$manufacturer_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET image = '" . $this->db->escape($data['image']) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		}

		if (isset($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('manufacturer');

		$this->event->trigger('post.admin.manufacturer.add', $manufacturer_id);

		return $manufacturer_id;
	}

	public function editManufacturer($manufacturer_id, $data) {
		$this->event->trigger('pre.admin.manufacturer.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET image = '" . $this->db->escape($data['image']) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		if (isset($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('manufacturer');

		$this->event->trigger('post.admin.manufacturer.edit');
	}

	public function deleteManufacturer($manufacturer_id) {
		$this->event->trigger('pre.admin.manufacturer.delete', $manufacturer_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "'");

		$this->cache->delete('manufacturer');

		$this->event->trigger('post.admin.manufacturer.delete', $manufacturer_id);
	}

	public function getManufacturer($manufacturer_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "') AS keyword FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row;
	}

	public function getManufacturers($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "manufacturer where status = 1 ";

		if (!empty($data['filter_name'])) {
			$sql .= " and name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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

	public function getManufacturerStores($manufacturer_id) {
		$manufacturer_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		foreach ($query->rows as $result) {
			$manufacturer_store_data[] = $result['store_id'];
		}

		return $manufacturer_store_data;
	}

	public function getTotalManufacturers() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "manufacturer WHERE  status = 1 ");

		return $query->row['total'];
	}

    public function edit_manufacturer_general($manufacturer_id,$data = array()){

        $this->event->trigger('pre.admin.manufacturer_general.edit', $data);

        $result=array();

        $image          =$data['image'];
        $name           =$data['name'];
        $sort_order     =$data['sort_order'];
        $location       =$data['location'];
        $short_name     =$data['short_name'];
        $phone          =$data['phone'];
        $email          =$data['email'];
        $address        =serialize(array('address'=>$data['address'],'city'=>$data['city'],'country'=>$data['country']));;
        $website        =$data['website'];
        $status         =$data['status'];

        $this->db->query("call edit_manufacturer_general($manufacturer_id,'$name','$image',$sort_order,'$location',
                                                '$short_name','$phone','$email','$address','$website',$status,
                                                @result,@reason)");

        $_result=get_object_vars($this->db->query("SELECT @result"));

        $_reason=get_object_vars($this->db->query("SELECT @reason"));

        $result[]=array('event_id'=>$manufacturer_id,'result'=>$_result['row']['@result'],'reason'=>$_reason['row']['@reason']);

        $this->cache->delete('event');

        $this->event->trigger('post.admin.manufacturer_general.edit', $manufacturer_id);


        return $result;
    }

    public function get_manufacturer_general($manufacturer_id,$status) {

        $result=array();

        if($manufacturer_id > 0){

            $query = $this->db->multi_query("CALL get_manufacturer_genaral($manufacturer_id,$status)");

            if(!empty($query)){

                foreach ($query as $value) {
                    foreach ($value as $key=>$val) {
                        $result[$key]=$val;
                    }
                    if(!empty($value['address'])){
                        $address= unserialize($value['address']);
                        $result['address']   = $address['address'];
                        $result['city']      = $address['city'];
                        $result['country']   = $address['country'];
                    }
                    else{
                        $result['address']   = '';
                        $result['city']      = '';
                        $result['country']   = '';
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

    public function edit_manufacturer_description($manufacturer_id,$data = array()){

        $this->event->trigger('pre.admin.manufacturer_description.edit', $data);

        $result=array();

        $i=0;

        foreach ($data['manufacturer_description'] as $key =>$val){

            $description        = $val['description'];

            $this->db->query("call edit_manufacturer_description($manufacturer_id,$key,'$description',@result,@reason)");

            $_result=get_object_vars($this->db->query("SELECT @result"));

            $_reason=get_object_vars($this->db->query("SELECT @reason"));

            $result[$i++]=array('event_id'=>$manufacturer_id,'result'=>$_result['row']['@result'],'reason'=>$_reason['row']['@reason']);

        }

        $this->cache->delete('event');

        $this->event->trigger('post.admin.manufacturer_description.edit', $manufacturer_id);

        return $result;
    }

    public function get_manufacturer_description($manufacturer_id){

        $result=array();

        if($manufacturer_id > 0){

            $query = $this->db->multi_query("CALL get_manufacturer_description($manufacturer_id)");

            if(!empty($query)){

                foreach ($query as $value) {

                    foreach ($value as $key => $val) {
                        $result[$value['language_id']][$key]=$val;
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
}
