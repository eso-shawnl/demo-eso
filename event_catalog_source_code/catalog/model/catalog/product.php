<?php
class ModelCatalogProduct extends Model {
    public function updateViewed($product_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
    }

    public function get_event_general($event_id) {
        /*
        [event_id] => 50
        [name] => 2015 Auckland special: Listen to Chinese · Evening
        [image] => catalog/product/poster_0.jpg
        [manufacturer_id] => 0
        [location] =>
        [publish_date] => 2015-04-23 03:04:48
        [start_date] => 2015-04-15 05:04:00
        [end_date] => 2015-05-15 12:05:00
        [minimum] => 1
        [maximum] => 1
        [sort_order] => 0
        [status] => 1
        [created_date] => 0000-00-00 00:00:00
        [modified_date] => 2015-04-03 03:15:11
           */

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

                    $address= unserialize($value['address']);

                    foreach ($value as $key => $val) {
                        $result[$value['language_id']][$key]=$val;
                    }
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

    public function getProducts($data = array()) {
        $sql = "SELECT p.event_id,
                      (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.event_id AND r1.status = '1' GROUP BY r1.product_id) AS rating,
                      (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.event_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount,
                      (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.event_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";

        if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "events_to_category p2c ON (cp.category_id = p2c.category_id)";
            } else {
                $sql .= " FROM " . DB_PREFIX . "events_to_category p2c";
            }

            if (!empty($data['filter_filter'])) {
                $sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.event_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "events p ON (pf.product_id = p.event_id)";
            } else {
                $sql .= " LEFT JOIN " . DB_PREFIX . "events p ON (p2c.event_id = p.event_id)";
            }
        } else {
            $sql .= " FROM " . DB_PREFIX . "events p";
        }

        $sql .= " LEFT JOIN " . DB_PREFIX . "events_description pd ON (p.event_id = pd.event_id) LEFT JOIN " . DB_PREFIX . "events_to_store p2s ON (p.event_id = p2s.event_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.publish_date <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

        if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
            } else {
                $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
            }

            if (!empty($data['filter_filter'])) {
                $implode = array();

                $filters = explode(',', $data['filter_filter']);

                foreach ($filters as $filter_id) {
                    $implode[] = (int)$filter_id;
                }

                $sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
            }
        }

        if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
            $sql .= " AND (";

            if (!empty($data['filter_name'])) {
                $implode = array();

                $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

                foreach ($words as $word) {
                    $implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
                }

                if ($implode) {
                    $sql .= " " . implode(" AND ", $implode) . "";
                }

                if (!empty($data['filter_description'])) {
                    $sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
                }
            }

            $sql .= ")";
        }
        /*
        if (!empty($data['filter_manufacturer_id'])) {
            $sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
        }
        */
        $sql .= " GROUP BY p.event_id";

        $sort_data = array(
            'pd.name',
            //'p.model',
            //'p.quantity',
            //'p.price',
            //'rating',
            'p.sort_order',
            'p.publish_date'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
                $sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
            } elseif ($data['sort'] == 'p.price') {
                $sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
            } else {
                $sql .= " ORDER BY " . $data['sort'];
            }
        } else {
            $sql .= " ORDER BY p.sort_order";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC, LCASE(pd.name) DESC";
        } else {
            $sql .= " ASC, LCASE(pd.name) ASC";
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

        $product_data = array();

        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $product_data[$result['event_id']] = $this->get_event_general($result['event_id']);
        }

        return $product_data;
    }

    public function getProductSpecials($data = array()) {
        $sql = "SELECT DISTINCT ps.product_id,
                                (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "events p ON (ps.product_id = p.event_id) LEFT JOIN " . DB_PREFIX . "events_description pd ON (p.event_id = pd.event_id) LEFT JOIN " . DB_PREFIX . "events_to_store p2s ON (p.event_id = p2s.event_id) WHERE p.status = '1' AND p.publish_date <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id";

        $sort_data = array(
            'pd.name',
            //'p.model',
            //'ps.price',
            //'rating',
            'p.sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
                $sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
            } else {
                $sql .= " ORDER BY " . $data['sort'];
            }
        } else {
            $sql .= " ORDER BY p.sort_order";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC, LCASE(pd.name) DESC";
        } else {
            $sql .= " ASC, LCASE(pd.name) ASC";
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

        $product_data = array();

        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $product_data[$result['event_id']] = $this->getProduct($result['event_id']);
        }

        return $product_data;
    }

    public function getLatestProducts($limit) {
        $product_data = $this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

        if (!$product_data) {
            $query = $this->db->query("SELECT p.event_id FROM " . DB_PREFIX . "events p LEFT JOIN " . DB_PREFIX . "events_to_store p2s ON (p.event_id = p2s.event_id) WHERE p.status = '1' AND p.publish_date <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.publish_date DESC LIMIT " . (int)$limit);

            foreach ($query->rows as $result) {
                $product_data[$result['event_id']] = $this->getProduct($result['event_id']);
            }

            $this->cache->set('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
        }

        return $product_data;
    }

    public function getPopularProducts($limit) {
        $product_data = array();

        $query = $this->db->query("SELECT p.event_id FROM " . DB_PREFIX . "events p LEFT JOIN " . DB_PREFIX . "events_to_store p2s ON (p.event_id = p2s.event_id) WHERE p.status = '1' AND p.publish_date <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed DESC, p.publish_date DESC LIMIT " . (int)$limit);

        foreach ($query->rows as $result) {
            $product_data[$result['event_id']] = $this->getProduct($result['event_id']);
        }

        return $product_data;
    }

    public function getBestSellerProducts($limit) {
        $product_data = $this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

        if (!$product_data) {
            $product_data = array();

            $query = $this->db->query("SELECT op.product_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "order o ON (op.order_id = o.order_id) LEFT JOIN " . DB_PREFIX . "events p ON (op.product_id = p.event_id) LEFT JOIN " . DB_PREFIX . "events_to_store p2s ON (p.event_id = p2s.event_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.publish_date <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);

            foreach ($query->rows as $result) {
                $product_data[$result['event_id']] = $this->getProduct($result['event_id']);
            }

            $this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
        }

        return $product_data;
    }

    public function get_event_attributes($product_id) {
        $product_attribute_group_data = array();

        $product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

        foreach ($product_attribute_group_query->rows as $product_attribute_group) {
            $product_attribute_data = array();

            $product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");

            foreach ($product_attribute_query->rows as $product_attribute) {
                $product_attribute_data[] = array(
                    'attribute_id' => $product_attribute['attribute_id'],
                    'name'         => $product_attribute['name'],
                    'text'         => $product_attribute['text']
                );
            }

            $product_attribute_group_data[] = array(
                'attribute_group_id' => $product_attribute_group['attribute_group_id'],
                'name'               => $product_attribute_group['name'],
                'attribute'          => $product_attribute_data
            );
        }

        return $product_attribute_group_data;
    }

    public function getProductOptions($product_id) {
        $product_option_data = array();

        $product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN " . DB_PREFIX . "option o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

        foreach ($product_option_query->rows as $product_option) {
            $product_option_value_data = array();

            $product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

            foreach ($product_option_value_query->rows as $product_option_value) {
                $product_option_value_data[] = array(
                    'product_option_value_id' => $product_option_value['product_option_value_id'],
                    'option_value_id'         => $product_option_value['option_value_id'],
                    'name'                    => $product_option_value['name'],
                    'image'                   => $product_option_value['image'],
                    'quantity'                => $product_option_value['quantity'],
                    'subtract'                => $product_option_value['subtract'],
                    'price'                   => $product_option_value['price'],
                    'price_prefix'            => $product_option_value['price_prefix'],
                    'weight'                  => $product_option_value['weight'],
                    'weight_prefix'           => $product_option_value['weight_prefix']
                );
            }

            $product_option_data[] = array(
                'product_option_id'    => $product_option['product_option_id'],
                'product_option_value' => $product_option_value_data,
                'option_id'            => $product_option['option_id'],
                'name'                 => $product_option['name'],
                'type'                 => $product_option['type'],
                'value'                => $product_option['value'],
                'required'             => $product_option['required']
            );
        }

        return $product_option_data;
    }

    public function getProductDiscounts($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

        return $query->rows;
    }

    public function get_event_images($event_id) {
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

    public function getProductLayoutId($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

        if ($query->num_rows) {
            return $query->row['layout_id'];
        } else {
            return 0;
        }
    }

    public function getTotalProducts($data = array()) {
        $sql = "SELECT COUNT(DISTINCT p.event_id) AS total";

        if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "events_to_category p2c ON (cp.category_id = p2c.category_id)";
            } else {
                $sql .= " FROM " . DB_PREFIX . "events_to_category p2c";
            }

            if (!empty($data['filter_filter'])) {
                $sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.event_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "events p ON (pf.product_id = p.event_id)";
            } else {
                $sql .= " LEFT JOIN " . DB_PREFIX . "events p ON (p2c.event_id = p.event_id)";
            }
        } else {
            $sql .= " FROM " . DB_PREFIX . "events p";
        }

        $sql .= " LEFT JOIN " . DB_PREFIX . "events_description pd ON (p.event_id = pd.event_id) LEFT JOIN " . DB_PREFIX . "events_to_store p2s ON (p.event_id = p2s.event_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.publish_date <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

        if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
            } else {
                $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
            }

            if (!empty($data['filter_filter'])) {
                $implode = array();

                $filters = explode(',', $data['filter_filter']);

                foreach ($filters as $filter_id) {
                    $implode[] = (int)$filter_id;
                }

                $sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
            }
        }

        if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
            $sql .= " AND (";

            if (!empty($data['filter_name'])) {
                $implode = array();

                $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

                foreach ($words as $word) {
                    $implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
                }

                if ($implode) {
                    $sql .= " " . implode(" AND ", $implode) . "";
                }

                if (!empty($data['filter_description'])) {
                    $sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
                }
            }

            $sql .= ")";
        }

        if (!empty($data['filter_manufacturer_id'])) {
            $sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function get_profiles($product_id) {
        return $this->db->query("SELECT pd.* FROM " . DB_PREFIX . "product_recurring pp JOIN " . DB_PREFIX . "recurring_description pd ON pd.language_id = " . (int)$this->config->get('config_language_id') . " AND pd.recurring_id = pp.recurring_id JOIN " . DB_PREFIX . "recurring p ON p.recurring_id = pd.recurring_id WHERE product_id = " . (int)$product_id . " AND status = 1 AND customer_group_id = " . (int)$this->config->get('config_customer_group_id') . " ORDER BY sort_order ASC")->rows;
    }

    public function getProfile($product_id, $recurring_id) {
        return $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring p JOIN " . DB_PREFIX . "product_recurring pp ON pp.recurring_id = p.recurring_id AND pp.product_id = " . (int)$product_id . " WHERE pp.recurring_id = " . (int)$recurring_id . " AND status = 1 AND pp.customer_group_id = " . (int)$this->config->get('config_customer_group_id'))->row;
    }

    public function getTotalProductSpecials() {
        $query = $this->db->query("SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "events p ON (ps.product_id = p.event_id) LEFT JOIN " . DB_PREFIX . "events_to_store p2s ON (p.event_id = p2s.event_id) WHERE p.status = '1' AND p.publish_date <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");

        if (isset($query->row['total'])) {
            return $query->row['total'];
        } else {
            return 0;
        }
    }

    public function getUVByProductID($product_id) {

        $this->db->multi_query("call calUVByProductID($product_id,@result)");

        $_result=get_object_vars($this->db->query("SELECT @result"));

        return $_result['row']['@result'];
    }

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
}
