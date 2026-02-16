<?php

class ControllerSetupTakmeen extends HController {

    protected function getAlias() {
        return 'setup/takmeen';
    }
    
    protected function getPrimaryKey() {
        return 'takmeen_id';
    }

    public function index()
    {
        $this->init();

        $this->getForm();
    }

    protected function getList() {
        parent::getList();
        
        $this->response->setOutput($this->render());
    }

    protected function getForm() {
        parent::getForm();
        
        if (isset($this->request->get[$this->getPrimaryKey()]) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $result = $this->model[$this->getAlias()]->getRow(array($this->getPrimaryKey() => $this->request->get[$this->getPrimaryKey()]));
            foreach($result as $field => $value) {
                $this->data[$field] = $value;
            }
        }
        
        $this->response->setOutput($this->render());
    }

    protected function insertData($data) {
        $this->model[$this->getAlias()]->deleteBulk($this->getAlias(), array('momin_id' => $data['momin_id']));
        return $this->model[$this->getAlias()]->add($this->getAlias(), $data);
    }

    protected function insertRedirect($id, $data) {
        $url = $this->getURL();
        $this->redirect($this->url->link($this->getAlias() . '/insert', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    }

	public function getAjaxLists() {
        
		$this->load->language($this->getAlias());
        $this->model[$this->getAlias()] = $this->load->model($this->getAlias());
        $data = array();
        $aColumns = array('sf_no', 'ejamaat_no', 'full_name', 'mohallah', 'amount', 'action');

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

            for ($i = 0; $i < count($aColumns); $i++) {
                if($aColumns[$i] == 'action') {
                    $row[] = $strAction;
                } else {
                    $row[] = $aRow[$aColumns[$i]];
                }
            }
            $output['aaData'][] = $row;
        }

        echo json_encode($output);
    }

    public function getMominBySF() {
        $sf_no = $this->request->post['sf_no'];
        $this->model['momin'] = $this->load->model('setup/momin');
        $where = "sfno='".$sf_no."' AND hof_ejamaat_no = ejamaat_no";
        $momin = $this->model['momin']->getRow($where);

        $this->model['takmeen'] = $this->load->model('setup/takmeen');
        $takmeen = $this->model['takmeen']->getRow(array('momin_id' => $momin['id']));

        $this->model['user_mohallah'] = $this->load->model('setup/user_mohallah');
        $user_mohallahs = $this->model['user_mohallah']->getArrays('mohallah_id','mohallah_id',array('user_id' =>$this->session->data['user_id']));
        if(isset($user_mohallahs[$momin['mohallah_id']])) {
            $this->model['mohallah'] = $this->load->model('setup/mohallah');
            $mohallah = $this->model['mohallah']->getRow(array('id' => $momin['mohallah_id']));
            $json = array(
                'success' => true,
                'momin_id' => $momin['id'],
                'sf_no' => $momin['sfno'],
                'ejamaat_no' => $momin['ejamaat_no'],
                'mohallah' => $mohallah['name'],
                'momin' => $momin['full_name'],
                'amount' => $takmeen['amount']
            );
        } else {
            $json = array(
                'success' => false,
                'error' => 'Invalid request',
            );
        }
        echo json_encode($json);
    }

}

?>