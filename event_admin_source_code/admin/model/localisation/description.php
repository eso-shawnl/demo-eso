<?php
class ModelLocalisationDescription extends Model {

	public function getDescriptions($filter_name,$index_id) {
        $sql = "SELECT * FROM tb_description where 1=1 ";

        $sql .= " and language_id = (SELECT
                                    language_id
                                FROM
                                    tb_description
                                WHERE
                                    description_id = (SELECT
                                            description_id
                                        FROM
                                            tb_caption_to_description
                                        WHERE
                                            index_id= ". $index_id .")) ";

        if ($filter_name) {
            $sql .= " and value LIKE '" . $this->db->escape($filter_name) . "%'";
        }

        if ($index_id){
            $sql .= " and description_id IN (SELECT
                                        description_id
                                    FROM
                                        tb_caption_to_description
                                    WHERE
                                        caption_id in (SELECT
                                                    caption_id
                                                FROM
                                                    tb_caption_to_description
                                                WHERE
                                                    index_id = ". $index_id .")) ";
        }

        $sql .= " ORDER BY value";

        $query = $this->db->query($sql);

        return $query->rows;
		
	}
	
}
