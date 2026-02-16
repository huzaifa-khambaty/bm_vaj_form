<?php

class ModelSetupMohallah extends HModel {

    protected function getAlias() {
        return 'setup/mohallah';
    }

    protected function getTable() {
        return 'mohallah';
    }

    public function getRow($filter=array(), $sort_order=array()) {
        $sql = "SELECT *";
        $sql .= " FROM `" . $this->getTable() . "`";
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
        $sql .= " FROM `" . $this->getTable() . "`";
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

    public function getArrays($field, $value, $filter=array(), $sort_order=array(), $value_separator) {
        $rows = $this->getRows($filter,$sort_order);
        $arrRows = array();
        foreach($rows as $row) {
            $strValue = '';
            $implode = array();
            if(is_array($value)) {
                foreach($value as $c => $v) {
                    $implode[] = $row[$v];
                }
                if($implode) {
                    $strValue = implode($value_separator,$implode);
                }
            } else {
                $strValue = $row[$value];
            }

            $arrRows[$row[$field]] = $strValue;
        }

        return $arrRows;
    }

}

?>