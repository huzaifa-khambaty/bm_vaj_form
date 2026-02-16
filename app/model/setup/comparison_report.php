<?php

class ModelSetupComparisonReport extends HModel {

    protected function getTable() {
        return 'takmeen';
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
            if(is_array($filter)) {
                $implode = array();
                foreach($filter as $column => $value) {
                    $implode[] = "`$column`='$value'";
                }
                $sql .= " WHERE " . implode(" AND ", $implode);
            } else {
                $sql .= " WHERE " . $filter;
            }
        }

        if($sort_order) {
            $sql .= " ORDER BY " . implode(',',$sort_order);
        }

        $query = $this->db->query($sql);
        return $query->rows;
    }

    private function getSubQuery() {
        $sql = "";
        $sql .="(SELECT v.takmeen_id, v.momin_id, m.sfno as sf_no, m.hof_ejamaat_no, m.ejamaat_no, m.full_name";
        $sql .=", m.mohallah_id, mh.name AS mohallah";
        $sql .=", v.amount, v.created_at";
        $sql .=" FROM _vaj_form_takmeen v";
        $sql .=" INNER JOIN momin m ON m.id = v.momin_id";
        $sql .=" INNER JOIN mohallah mh ON mh.id = m.mohallah_id) as a";

        return $sql;
    }

}

?>