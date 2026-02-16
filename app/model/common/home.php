<?php

class ModelCommonHome extends HModel {

    protected function getAlias() {
        return 'common/home';
    }

//    protected function getTable() {
//        return 'form_printing_log';
//    }

    public function getFormPrintedData($filter) {
        $sql = "SELECT COUNT(DISTINCT m.id) AS total, COUNT(DISTINCT p.momin_id) AS printed";
        $sql .= " FROM momin m";
        $sql .= " LEFT JOIN _vaj_form_form_printing_log p ON p.momin_id = m.id";
        if($filter['mohallah_id']) {
            $sql .= " WHERE m.mohallah_id = '" . $filter['mohallah_id'] . "'";
        } else {
            $sql .= " WHERE m.mohallah_id IN (" . $filter['mohallahs'] . ')';
        }
        $sql .= " AND m.ejamaat_no = m.hof_ejamaat_no";

        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getTakmeenData($filter) {
        $sql = "SELECT COUNT(DISTINCT m.id) AS total, COUNT(DISTINCT t.momin_id) AS takmeen";
        $sql .= " FROM momin m";
        $sql .= " LEFT JOIN _vaj_form_takmeen t ON t.momin_id = m.id";
        if($filter['mohallah_id']) {
            $sql .= " WHERE m.mohallah_id = '" . $filter['mohallah_id'] . "'";
        } else {
            $sql .= " WHERE m.mohallah_id IN (" . $filter['mohallahs'] . ')';
        }
        $sql .= " AND m.ejamaat_no = m.hof_ejamaat_no";

        $query = $this->db->query($sql);
        return $query->row;
    }

}

?>