<?php
class ModelLocalisationCaption extends Model {

	public function getCaptions($data = array()) {
			$sql = "SELECT * FROM tb_caption";

			if (!empty($data['filter_name'])) {
				$sql .= " where name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
			}
			
			$sql .= " GROUP BY caption_id";

			$sort_data = array(
				'caption_id',
				'name'
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

	public function getCaptionByPageIDAndLangID($page_id,$language_id){
		$page_caption = array();
		$mysqli = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		
		$result = $mysqli->query("CALL getCaptionByPageIDAndLangID($page_id,$language_id)") or die("Query fail: " . mysqli_error($mysqli));
        $result = get_object_vars($result);

        foreach ($result['rows'] as $val) {        
        	$page_caption[]=array('id'=>$val['index_id'],'name'=>$val['name'],'value'=>$val['value'],'caption_id'=>$val['caption_id']);
	    }
        return $page_caption;
	}
	
	public function getCaptionByPageID($page_id){
		$page_caption = array();
		$mysqli = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	
		$result = $mysqli->query("CALL getCaptionByPageID($page_id)") or die("Query fail: " . mysqli_error($mysqli));
		$result = get_object_vars($result);
	
		foreach ($result['rows'] as $val) {
			$page_caption[]=array('id'=>$val['index_id'],'name'=>$val['name'],'value'=>base64_decode($val['value']),'caption_id'=>$val['caption_id']);
		}
		return $page_caption;
	}
	
}
