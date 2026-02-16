<?php

class ControllerUserUserPermission extends HController {

    protected function getAlias() {
        return 'user/user_permission';
    }

    protected function getPrimaryKey() {
        return 'user_permission_id';
    }

    protected function getList() {
        parent::getList();

        $results = $this->getListData();
        foreach ($results['lists'] as $result) {
            $action = array();

            $action[] = array(
                'text' => $this->language->get('text_edit'),
                'href' => $this->url->link('user/user_permission/update', 'token=' . $this->session->data['token'] . '&user_permission_id=' . $result['user_permission_id'], 'SSL')
            );

            $this->data['user_permissions'][] = array(
                'user_permission_id' => $result['user_permission_id'],
                'name' => $result['name'],
                'selected' => isset($this->request->post['selected']) && in_array($result['user_permission_id'], $this->request->post['selected']),
                'action' => $action
            );
        }

        $ignore_columns = array($this->getPrimaryKey(),'selected','action');
        parent::setSortURL($this->data['user_permissions'], $ignore_columns);

//        d($this->data['order'],true);

        $this->data['action_ajax'] = $this->url->link($this->getAlias() . '/getAjaxLists', 'token=' . $this->session->data['token'], 'SSL');
        $this->response->setOutput($this->render());
    }

    protected function getForm() {
        parent::getForm();

        if (isset($this->request->get[$this->getPrimaryKey()]) && $this->request->server['REQUEST_METHOD'] != 'POST') {
            $user_permission_info = $this->model[$this->getAlias()]->getUserPermission($this->request->get[$this->getPrimaryKey()]);
        }

        if (isset($this->request->post['name'])) {
            $this->data['name'] = $this->request->post['name'];
        } elseif (!empty($user_permission_info)) {
            $this->data['name'] = $user_permission_info['name'];
        } else {
            $this->data['name'] = '';
        }

        $ignore = array(
            'common/home',
            'common/startup',
            'common/login',
            'common/preset',
            'common/logout',
            'common/forgotten',
            'common/reset',
            'error/not_found',
            'error/permission',
            'error/security',
            'user/user_permission',
            'common/footer',
            'common/header'
        );

        $this->data['permissions'] = array();

        $files = glob(DIR_APPLICATION . 'controller/*/*.php');

        foreach ($files as $file) {
            $data = explode('/', dirname($file));

            $permission = end($data) . '/' . basename($file, '.php');

            if (!in_array($permission, $ignore)) {
                $this->data['permissions'][$permission] = array(
                    'view' => '0',
                    'insert' => '0',
                    'update' => '0',
                    'delete' => '0',
                    'print' => '0',
                    'reprint' => '0',
                );
            }
        }

        if (isset($this->request->post['permission'])) {
            $this->data['permissions'] = array_merge($this->data['permissions'],$this->request->post['permission']);
        } elseif (isset($user_permission_info['permission'])) {
            $this->data['permissions'] = array_merge($this->data['permissions'],$user_permission_info['permission']);
        }

        $this->response->setOutput($this->render());
    }

    protected function validateForm() {
        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('delete', 'user/user_permission')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('user/user');

        foreach ($this->request->post['selected'] as $user_permission_id) {
            $user_total = $this->model_user_user->getTotalUsersByPermissionId($user_permission_id);

            if ($user_total) {
                $this->error['warning'] = sprintf($this->language->get('error_user'), $user_total);
            }
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function getAjaxLists() {

        $this->load->language('user/user_permission');
        $this->model[$this->getAlias()] = $this->load->model($this->getAlias());
        $data = array();
        $aColumns = array('action','name');

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
                    $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
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
                if($aColumns[$i]=='permission_group') {
                    $row[] = $arrUserPermissions[$aRow['user_permission_id']];
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