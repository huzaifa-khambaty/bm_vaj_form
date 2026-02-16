<?php

class ModelUserUserPermission extends HModel {

    protected function getAlias() {
        return 'user/user_permission';
    }
    
    protected function getTable() {
        return 'user_permission';
    }
    
    public function add($document, $data) {
        $data['permission'] = (isset($data['permission']) ? serialize($data['permission']) : '');
        $this->hinsert($document, $this->getTable(), $data);
//        $this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', password = '" . $this->db->escape(md5($data['password'])) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', user_permission_id = '" . (int) $data['user_permission_id'] . "', status = '" . (int) $data['status'] . "', date_added = NOW()");
    }

    public function edit($document, $primary_key, $data) {
        $data['permission'] = (isset($data['permission']) ? serialize($data['permission']) : '');
//        d($data,true);
        $this->hupdate($document, $this->getTable(), $primary_key, $data);
    }

    public function delete($document, $primary_key) {
        $this->hdelete($document, $this->getTable(), $primary_key);
//        $this->db->query("DELETE FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int) $user_id . "'");
    }

    public function addPermission($user_id, $type, $page) {
        $user_query = $this->db->query("SELECT DISTINCT user_permission_id FROM " . DB_PREFIX . "user WHERE user_id = '" . $user_id . "'");

        if ($user_query->num_rows) {
            $user_permission_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_permission WHERE user_permission_id = '" . $user_query->row['user_permission_id'] . "'");

            if ($user_permission_query->num_rows) {
                $data = unserialize($user_permission_query->row['permission']);

                $data[$type][] = $page;

                $this->db->query("UPDATE " . DB_PREFIX . "user_permission SET permission = '" . serialize($data) . "' WHERE user_permission_id = '" . $user_query->row['user_permission_id'] . "'");
            }
        }
    }

    public function getUserPermission($user_permission_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_permission WHERE user_permission_id = '" .  $user_permission_id . "'");

        $user_permission = array(
            'name' => $query->row['name'],
            'permission' => unserialize($query->row['permission'])
        );

        return $user_permission;
    }

    public function getUserPermissions($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "user_permission";

        $sql .= " ORDER BY name";

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

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalUserPermissions() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user_permission");

        return $query->row['total'];
    }

}

?>