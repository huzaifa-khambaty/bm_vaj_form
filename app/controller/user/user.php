<?php

class ControllerUserUser extends HController {

    protected function getAlias() {
        return 'user/user';
    }
    
    protected function getPrimaryKey() {
        return 'user_id';
    }
    
    protected function getList() {
        parent::getList();
        
        $this->response->setOutput($this->render());
    }

    protected function getForm() {
        parent::getForm();

        if (isset($this->request->get['user_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $result = $this->model[$this->getAlias()]->getRow(array($this->getPrimaryKey() => $this->request->get['user_id']));
            foreach($result as $field => $value) {
                $this->data[$field] = $value;
            }

            $this->model['user_mohallah'] = $this->load->model('setup/user_mohallah');
            $user_mohallahs = $this->model['user_mohallah']->getRows(array('user_id' => $this->request->get['user_id']));
            foreach($user_mohallahs as $user_mohallah) {
                $this->data['user_mohallahs'][] = $user_mohallah['mohallah_id'];
            }

        }

        $this->model['user_permission'] = $this->load->model('user/user_permission');
        $this->data['user_permissions'] = $this->model['user_permission']->getuserPermissions();
        
        $this->model['mohallah'] = $this->load->model('setup/mohallah');
        $this->data['mohallahs'] = $this->model['mohallah']->getRows();

        if (isset($this->request->get['user_id'])) {
            $this->data['strValidation'] = "{
                'rules': {
                    'username': {'required': true, 'minlength': 3, 'maxlength': 16},
                    'firstname': {'required': true, 'minlength': 3, 'maxlength': 16},
                    'lastname': {'required': true, 'minlength': 3, 'maxlength': 16},
                    'password': {'minlength': 8},
                    'confirm': {'equalTo': '#Password'}
                },
                'messages': {
                    'username': {'required': '" . $this->language->get('error_username') . "', 'minlength': '" . $this->language->get('error_name') . "', 'maxlength': '" . $this->language->get('error_username') . "'},
                    'firstname': {'required': '" . $this->language->get('error_firstname') . "', 'minlength': '" . $this->language->get('error_first_name') . "', 'maxlength': '" . $this->language->get('error_firstname') . "'},
                    'lastname': {'required': '" . $this->language->get('error_lastname') . "', 'minlength': '" . $this->language->get('error_last_name') . "', 'maxlength': '" . $this->language->get('error_lastname') . "'},
                    'password': {'minlength': '" . $this->language->get('error_password') . "'},
                    'confirm': {'equalTo': '" . $this->language->get('error_confirm_match') . "'}
                },
            }";
        } else {
            $this->data['strValidation'] = "{
                'rules': {
                    'username': {'required': true, 'minlength': 3, 'maxlength': 16},
                    'firstname': {'required': true, 'minlength': 3, 'maxlength': 16},
                    'lastname': {'required': true, 'minlength': 3, 'maxlength': 16},
                    'password': {'required': true, 'minlength': 8},
                    'confirm': {'required': true, 'equalTo': '#Password'}
                },
                'messages': {
                    'username': {'required': '" . $this->language->get('error_username') . "', 'minlength': '" . $this->language->get('error_username') . "', 'maxlength': '" . $this->language->get('error_username') . "'},
                    'firstname': {'required': '" . $this->language->get('error_firstname') . "', 'minlength': '" . $this->language->get('error_firstname') . "', 'maxlength': '" . $this->language->get('error_firstname') . "'},
                    'lastname': {'required': '" . $this->language->get('error_lastname') . "', 'minlength': '" . $this->language->get('error_lastname') . "', 'maxlength': '" . $this->language->get('error_lastname') . "'},
                    'password': {'required': '" . $this->language->get('error_password') . "', 'minlength': '" . $this->language->get('error_password') . "'},
                    'confirm': {'required': '" . $this->language->get('error_password') . "', 'equalTo': '" . $this->language->get('error_confirm_match') . "'}
                },
            }";
        }

        $this->response->setOutput($this->render());
    }

    protected function insertData($data) {
        //d($data, true);
        if($data['password']) {
            $data['password'] = md5($data['password']);
        } else {
            unset($data['password']);
        }

        $user_id = $this->model[$this->getAlias()]->add($this->getAlias(), $data);
        if(isset($data['user_mohallahs'])) {
            $user_mohallahs = $data['user_mohallahs'];
            unset($data['user_mohallahs']);
            $this->model['user_mohallah'] = $this->load->model('setup/user_mohallah');
            foreach($user_mohallahs as $user_mohallah) {
                $user_mohallah_id = $this->model['user_mohallah']->add($this->getAlias(), array('user_id' => $user_id, 'mohallah_id' => $user_mohallah));
            }
        }
        //d(array($data, $user_id, $user_mohallah_id), true);
    }

    protected function updateData($primary_key, $data) {
        if($data['password']) {
            $data['password'] = md5($data['password']);
        } else {
            unset($data['password']);
        }

        $this->model['user_mohallah'] = $this->load->model('setup/user_mohallah');
        $this->model['user_mohallah']->deleteBulk($this->getAlias(),array('user_id' => $primary_key));

        if(isset($data['user_mohallahs'])) {
            $user_mohallahs = $data['user_mohallahs'];
            unset($data['user_mohallahs']);
            foreach($user_mohallahs as $user_mohallah) {
                $this->model['user_mohallah']->add($this->getAlias(), array('user_id' => $primary_key, 'mohallah_id' => $user_mohallah));
            }
        }
        $this->model[$this->getAlias()]->edit($this->getAlias(), $primary_key, $data);
    }

    protected function deleteData($primary_key, $data) {
        $this->model['user_mohallah'] = $this->load->model('setup/user_mohallah');
        $this->model['user_mohallah']->deleteBulk($this->getAlias(),array('user_id' => $primary_key));

        $this->model[$this->getAlias()]->delete($this->getAlias(), $primary_key);
    }

    protected function validateForm() {
        if ((utf8_strlen($this->request->post['username']) < 3) || (utf8_strlen($this->request->post['username']) > 64)) {
            $this->error['username'] = $this->language->get('error_username');
        }

        if ((utf8_strlen($this->request->post['firstname']) < 3) || (utf8_strlen($this->request->post['firstname']) > 64)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['lastname']) < 3) || (utf8_strlen($this->request->post['lastname']) > 64)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
	
	public function getAjaxLists() {
        
		$this->load->language('user/user');
        $this->model[$this->getAlias()] = $this->load->model($this->getAlias());
        $this->model['permission'] = $this->load->model('user/user_permission');
        $arrPermissions = $this->model['permission']->getArrays('user_permission_id','name');
        $data = array();
        $aColumns = array('action','username','user_permission_id','created_at');

        /*
         * Paging
         */
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $data['criteria']['start'] = $_GET['iDisplayStart'];
            $data['criteria']['limit'] = $_GET['iDisplayLength'];
        }

        /*
         * Ordering
         */
        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = " ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= "`" . $aColumns[intval($_GET['iSortCol_' . $i])] . "` " .
                            ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == " ORDER BY") {
                $sOrder = "";
            }
            $data['criteria']['orderby'] = $sOrder;
        }


        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $sWhere = "";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch'] != '') {
                    $sWhere .= "LOWER(`" . $aColumns[$i] . "`) LIKE '%" . strtolower(mysql_real_escape_string($_GET['sSearch'])) . "%' OR ";
                }
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
            }
        }

        if ($sWhere != "") {
            $data['filter']['RAW'] = substr($sWhere, 5, strlen($sWhere) - 5);
        }

        //d($data);
        $results = $this->model[$this->getAlias()]->getLists($data);
        $iFilteredTotal = $results['total'];
        $iTotal = $results['table_total'];


        /*
         * Output
         */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($results['lists'] as $aRow) {
            $row = array();
            $actions = array();

            $actions[] = array(
                'text' => $this->language->get('text_edit'),
                'href' => $this->url->link($this->getAlias() . '/update', 'token=' . $this->session->data['token'] . '&' . $this->getPrimaryKey() . '=' . $aRow[$this->getPrimaryKey()], 'SSL'),
                'class' => 'fa fa-pencil'
            );

            $actions[] = array(
                'text' => $this->language->get('text_delete'),
                'href' => 'javascript:void(0);',
                'click' => "ConfirmDelete('" . $this->url->link($this->getAlias() . '/delete', 'token=' . $this->session->data['token'] . '&id=' . $aRow[$this->getPrimaryKey()], 'SSL') . "')",
                'class' => 'fa fa-times'
            );


            $strAction = '';
            foreach ($actions as $action) {
                $strAction .= '<a href="' . $action['href'] . '" title="' . $action['text'] . '" ' . (isset($action['click']) ? 'onClick="' . $action['click'] . '"' : '') . '>';
                if (isset($action['class'])) {
                    $strAction .= '<span class="' . $action['class'] . '"></span>';
                } else {
                    $strAction .= $action['text'];
                }
                $strAction .= '</a>&nbsp;';
            }

            $row[] = $strAction;
            for ($i = 1; $i < count($aColumns); $i++) {
                if($aColumns[$i]=='user_permission_id') {
                    $row[] = $arrPermissions[$aRow['user_permission_id']];
                } else {
                    $row[] = $aRow[$aColumns[$i]];
                }

            }
            $output['aaData'][] = $row;
        }

        echo json_encode($output);
    }

}

?>