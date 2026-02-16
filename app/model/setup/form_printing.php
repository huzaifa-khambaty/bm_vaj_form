<?php

class ModelSetupFormPrinting extends HModel {

    protected function getAlias() {
        return 'setup/form_printing';
    }

    protected function getTable() {
        return 'form_printing';
    }

    public function getLists($data) {
        $filterSQL = $this->getFilterString($data['filter']);
        $criteriaSQL = $this->getCriteriaString($data['criteria']);

        $sql = "SELECT count(*) as total";
        $sql .= " FROM " . $this->getSubQuery();
        $query = $this->db->query($sql);
        $table_total = $query->row['total'];

        $sql = "SELECT count(*) as total";
        $sql .= " FROM " . $this->getSubQuery();
        if($filterSQL) {
            $sql .= " WHERE " . $filterSQL;
        }

        $query = $this->db->query($sql);
        $total = $query->row['total'];

        $sql = "SELECT *";
        $sql .= " FROM " . $this->getSubQuery();
        if($filterSQL) {
            $sql .= " WHERE " . $filterSQL;
        }
        if($criteriaSQL) {
            $sql .= $criteriaSQL;
        }


        $query = $this->db->query($sql);
//        d(array($data,$sql,$query));
        $lists = $query->rows;

//        d($lists);

        return array('table_total' => $table_total, 'total' => $total, 'lists' => $lists);

    }

    public function getRow($filter=array(), $sort_order=array()) {
        $sql = "SELECT *";
        $sql .= " FROM " . $this->getSubQuery();
        if($filter) {
            $implode = array();
            foreach($filter as $column => $value) {
                $implode[] = "`$column`='$value'";
            }
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        if($sort_order) {
            $sql .= " ORDER BY " . implode(',',$sort_order);
        }

        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getRows($filter=array(), $sort_order=array()) {
        $sql = "SELECT *";
        $sql .= " FROM " . $this->getSubQuery();
        if($filter) {
            $implode = array();
            foreach($filter as $column => $value) {
                $implode[] = "`$column`='$value'";
            }
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        if($sort_order) {
            $sql .= " ORDER BY " . implode(',',$sort_order);
        }

        $query = $this->db->query($sql);
        return $query->rows;
    }

    private function getSubQuery() {
        $sql = "";
        $sql .="(SELECT v.form_printing_id, m.sfno as sf_no, m.hof_ejamaat_no, m.ejamaat_no, m.full_name";
        $sql .=", m.mohallah_id, mh.name AS mohallah";
        $sql .=", v.previous_amount, v.last_amount";
        $sql .=" FROM _vaj_form_form_printing v";
        $sql .=" INNER JOIN momin m ON m.id = v.momin_id";
        $sql .=" INNER JOIN mohallah mh ON mh.id = m.mohallah_id) as a";

        return $sql;
    }

    public function getCurrentYear() {
        $sql = "SELECT *";
        $sql .= " FROM `_vaj_form_year`";

        $query = $this->db->query($sql);
        return ($query->row['year'] + 1);
    }

}

?>