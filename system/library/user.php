<?php

final class User {

    private $user_id;
    private $username;
    private $full_name;
    private $permission = array();

    public function __construct($registry) {
        $this->db = $registry->get('db');
        $this->request = $registry->get('request');
        $this->session = $registry->get('session');

        if (isset($this->session->data['user_id'])) {
            $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . $this->session->data['user_id'] . "'");

            if ($user_query->num_rows) {
                $this->user_id = $user_query->row['user_id'];
                $this->username = $user_query->row['username'];
                $this->full_name = trim($user_query->row['firstname'] . ' ' . $user_query->row['lastname']);

                $this->db->query("UPDATE " . DB_PREFIX . "user SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . $this->session->data['user_id'] . "'");

                $user_permission_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_permission WHERE user_permission_id = '" . $user_query->row['user_permission_id'] . "'");

                $permissions = unserialize($user_permission_query->row['permission']);

                if (is_array($permissions)) {
                    foreach ($permissions as $key => $value) {
                        $this->permission[$key] = $value;
                    }
                }
            } else {
                $this->logout();
            }
        }
    }

    public function login($username, $password) {
        $sql = "SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND password = '" . $this->db->escape(md5($password)) . "'";
        $user_query = $this->db->query($sql);

        if ($user_query->num_rows) {
            $this->session->data['user_id'] = $user_query->row['user_id'];

            $this->user_id = $user_query->row['user_id'];
            $this->employee_id = $user_query->row['employee_id'];
            $this->username = $user_query->row['username'];

            $sql = "SELECT permission FROM " . DB_PREFIX . "user_permission WHERE user_permission_id = '" . $user_query->row['user_permission_id'] . "'";
            $user_permission_query = $this->db->query($sql);

            $permissions = unserialize($user_permission_query->row['permission']);

            if (is_array($permissions)) {
                foreach ($permissions as $key => $value) {
                    $this->permission[$key] = $value;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function logout() {
        unset($this->session->data['user_id']);

        $this->user_id = '';
        $this->employee_id = '';
        $this->username = '';

        session_destroy();
    }

    public function hasPermission($key, $value) {
        if (isset($this->permission[$value][$key])) {
//            return in_array($value, $this->permission[$key]);
            return $this->permission[$value][$key];
        } else {
            return false;
        }
    }

    public function isLogged() {
//        d(array($this->user_id, $this->employee_id, $this->username));
        return $this->user_id;
    }

    public function getId() {
        return $this->user_id;
    }

    public function getFullName() {
        if($this->full_name)
            return $this->full_name;
        else
            return $this->username;
    }

    public function getUserName() {
        return $this->username;
    }

    public function getAllPermission() {
        return $this->permission;
    }

}

?>