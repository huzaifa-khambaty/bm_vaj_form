<?php

class HModel extends Model {

    protected function getPrimaryKey() {
        $column = $this->getPrimaryKeyColumn($this->getTable());
        return $column['column'];
    }

    public function getRow($filter=array(), $sort_order=array()) {
        $sql = "SELECT *";
        $sql .= " FROM `" . DB_PREFIX . $this->getTable() . "`";
        if($filter) {
            if(is_array($filter)) {
                $table_columns = $this->getTableColumns($this->getTable());
                $implode = array();
                foreach($filter as $column => $value) {
                    if(in_array($column,$table_columns)) {
                        $implode[] = "`$column`='$value'";
                    }
                }
                if($implode)
                    $sql .= " WHERE " . implode(" AND ", $implode);
            } else {
                $sql .= " WHERE " . $filter;
            }
        }

        if($sort_order) {
            $sql .= " ORDER BY " . implode(',',$sort_order);
        }

        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getRows($filter=array(), $sort_order=array()) {
        $sql = "SELECT *";
        $sql .= " FROM `" . DB_PREFIX . $this->getTable() . "`";
        if($filter) {
            if(is_array($filter)) {
                $table_columns = $this->getTableColumns($this->getTable());
                $implode = array();
                foreach($filter as $column => $value) {
                    if(in_array($column,$table_columns)) {
                        $implode[] = "`$column`='$value'";
                    }
                }
                if($implode)
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

    public function getLists($data) {
        $filterSQL = $this->getFilterString($data['filter']);
        $criteriaSQL = $this->getCriteriaString($data['criteria']);

        $sql = "SELECT count(*) as total";
        $sql .= " FROM `" . DB_PREFIX . $this->getTable() . "`";
        $query = $this->db->query($sql);
        $table_total = $query->row['total'];

        $sql = "SELECT count(*) as total";
        $sql .= " FROM `" . DB_PREFIX . $this->getTable() . "`";
        if($filterSQL) {
            $sql .= " WHERE " . $filterSQL;
        }

        $query = $this->db->query($sql);
        $total = $query->row['total'];

        $sql = "SELECT *";
        $sql .= " FROM `" . DB_PREFIX . $this->getTable() . "`";
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

    protected function getCriteriaString($criteria) {
        $sql = '';
        if (isset($criteria['orderby']) && $criteria['orderby']) {
            $sql .= $criteria['orderby'];
        } elseif (isset($criteria['order']) && $criteria['order']) {
            $sql .= " ORDER BY " . $criteria['order'];
            if(isset($criteria['sort']) && $criteria['sort']) {
                $sql .= " " . $criteria['sort'];
            } else {
                $sql .= " DESC";
            }
        }

        if (isset($criteria['start']) || isset($criteria['limit'])) {
            if ($criteria['start'] < 0) {
                $criteria['start'] = 0;
            }

            if ($criteria['limit'] < 1) {
                $criteria['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $criteria['start'] . "," . (int) $criteria['limit'];
        }

        return $sql;
    }

    protected function getFilterString($filter) {
        $cond = array();
        if(isset($filter['RAW']) && $filter['RAW']) {
            return $filter['RAW'];
        } else {
            if(isset($filter['EQ'])) {
                $cond = array_merge($cond,$this->getFilterEQ($filter['EQ']));
            }
            if(isset($filter['LT'])) {
                $cond = array_merge($cond,$this->getFilterLT($filter['LT']));
            }
            if(isset($filter['LTE'])) {
                $cond = array_merge($cond,$this->getFilterLTE($filter['LTE']));
            }
            if(isset($filter['GT'])) {
                $cond = array_merge($cond,$this->getFilterGT($filter['GT']));
            }
            if(isset($filter['GTE'])) {
                $cond = array_merge($cond,$this->getFilterGTE($filter['GTE']));
            }
            if(isset($filter['LKB'])) {
                $cond = array_merge($cond,$this->getFilterLKB($filter['LKB']));
            }
            if(isset($filter['LKF'])) {
                $cond = array_merge($cond,$this->getFilterLKF($filter['LKF']));
            }
            if(isset($filter['LKE'])) {
                $cond = array_merge($cond,$this->getFilterLKE($filter['LKE']));
            }
            return implode(' AND ', $cond);

        }
    }

    private function getFilterEQ($data) {
        $cond = array();
        foreach($data as $column => $value) {
            if(!empty($value)) {
                $cond[] = $column . "='" . addslashes($value) . "'";
            }
        }
        return $cond;
    }

    private function getFilterGT($data) {
        $cond = array();
        foreach($data as $column => $value) {
            if(!empty($value)) {
                $cond[] = $column . ">'" . addslashes($value) . "'";
            }
        }
        return $cond;
    }

    private function getFilterGTE($data) {
        $cond = array();
        foreach($data as $column => $value) {
            if(!empty($value)) {
                $cond[] = $column . ">='" . addslashes($value) . "'";
            }
        }
        return $cond;
    }

    private function getFilterLT($data) {
        $cond = array();
        foreach($data as $column => $value) {
            if(!empty($value)) {
                $cond[] = $column . " < '" . addslashes($value) . "'";
            }
        }
        return $cond;
    }

    private function getFilterLTE($data) {
        $cond = array();
        foreach($data as $column => $value) {
            if(!empty($value)) {
                $cond[] = $column . "<='" . addslashes($value) . "'";
            }
        }
        return $cond;
    }

    private function getFilterLKB($data) {
        $cond = array();
        foreach($data as $column => $value) {
            if(!empty($value)) {
                $cond[] = $column . " LIKE '%" . addslashes($value) . "%'";
            }
        }
        return $cond;
    }

    private function getFilterLKF($data) {
        $cond = array();
        foreach($data as $column => $value) {
            if(!empty($value)) {
                $cond[] = $column . " LIKE '%" . addslashes($value) . "'";
            }
        }
        return $cond;
    }

    private function getFilterLKE($data) {
        $cond = array();
        foreach($data as $column => $value) {
            if(!empty($value)) {
                $cond[] = $column . " LIKE '" . addslashes($value) . "%'";
            }
        }
        return $cond;
    }

    public function add($document, $data) {
        return $this->hinsert($document, $this->getTable(), $data);
    }

    protected function hinsert($document, $table, $data) {
        if(!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        if(!isset($data['created_by_id'])) {
            $data['created_by_id'] = $this->user->getID();
        }
        $table_column = $this->getTableColumns($table);
        $primary_column = $this->getPrimaryKeyColumn($table);
        if(!$primary_column['is_auto_increment']) {
//            $data[$table . '_id'] = getGUID();
            if(!isset($data[$primary_column['column']])) {
                $data[$primary_column['column']] = getGUID();
            }
        }
        $sql = "INSERT INTO `" . DB_PREFIX . $table . "` SET ";
        foreach($data as $column => $value) {
            if(in_array($column, $table_column)) {
                $sql .= " `" . $column . "` = '" . $this->db->escape($value) . "',";
            }
        }

        $sql = substr($sql,0, strlen($sql)-1);

//        d($sql);
        $this->db->query($sql);
        if($primary_column['is_auto_increment']) {
            $insert_id = $this->db->getLastId();
        } else {
            $insert_id = $data[$primary_column['column']];
        }
//        d(array($table, $sql, $insert_id,$primary_column),true);
        $this->audit("INSERT",$document, $table, $insert_id, $data);

        return $insert_id;
    }

    public function edit($document, $id, $data) {
        return $this->hupdate($document, $this->getTable(), $id, $data);
    }

    protected function hupdate($document, $table, $id, $data) {
        $table_column = $this->getTableColumns($table);
        $sql = "UPDATE `" . DB_PREFIX . $table . "` SET";
        foreach($data as $column => $value) {
            if(in_array($column, $table_column)) {
                $sql .= " `" . $column . "` = '" . $this->db->escape($value) . "',";
            }
        }

        $sql = substr($sql,0, strlen($sql)-1);
        $sql .= " WHERE `" . $this->getPrimaryKey() . "` = '" . $id  . "'";
//        d(array($document, $table, $id, $data, $table_column, $sql),true);
        $this->db->query($sql);

        $this->audit("UPDATE",$document, $table, $id, $data);
        return $id;
    }

    public function delete($document, $id) {
        $this->hdelete($document, $this->getTable(), $id);
    }

    protected function hdelete($document, $table, $id) {
        $row = $this->getRow(array($this->getPrimaryKey() => $id));

        $sql = "DELETE FROM `" . DB_PREFIX . $table . "` WHERE `" . $this->getPrimaryKey() . "` = '" . $id  . "'";
        $this->db->query($sql);

        $this->audit("DELETE",$document, $table, $id, $row);
    }

    private function audit($transaction_type, $document, $transaction_table, $transaction_id, $data = array()) {
        $sql = "SELECT CONNECTION_ID() as connection_id";
        $query = $this->db->query($sql);
        $connection_id = $query->row['connection_id'];

        $sql = "INSERT INTO `" . DB_PREFIX . "audit` SET";
        $sql .= " batch_identity = '" . $connection_id . "'";
        $sql .= ", document = '" . $document . "'";
        $sql .= ", transaction_type = '" . $transaction_type . "'";
        $sql .= ", transaction_table = '" . $transaction_table . "'";
        $sql .= ", transaction_id = '" . $transaction_id . "'";
        $sql .= ", created_by_id = '" . $this->user->getId() . "'";
        $sql .= ", created_at = '" . date('Y-m-d H:i:s') . "'";
        $this->db->query($sql);
        $audit_id = $this->db->getLastId();

        if(!empty($data)) {
            foreach($data as $column => $value) {
                $sql = "INSERT INTO `" . DB_PREFIX . "audit_detail` SET";
                $sql .= " audit_id = '" . $audit_id . "'";
                $sql .= ", field = '" . $column . "'";
                $sql .= ", value = '" . $this->db->escape($value) . "'";

                $this->db->query($sql);
            }
        }
    }

    protected function getTableColumns($table) {
        $table_column = array();
        $sql = "SHOW COLUMNS FROM `" . DB_PREFIX . $table . "`";
        $query = $this->db->query($sql);
        $rows = $query->rows;
        foreach($rows as $row) {
            $table_column[] = $row['Field'];
        }
        return $table_column;
    }

    private function getPrimaryKeyColumn($table) {
        $column = array();
        $sql = "SHOW COLUMNS FROM `" . DB_PREFIX . $table . "`";
        $query = $this->db->query($sql);
        $rows = $query->rows;
        foreach($rows as $row) {
            if($row['Key'] == 'PRI') {
                if($row['Extra'] == 'auto_increment') {
                    $is_auto_increment = 1;
                } else {
                    $is_auto_increment = 0;
                }
                $column = array(
                    'column' => $row['Field'],
                    'is_auto_increment' => $is_auto_increment
                );
            }
        }
        return $column;
    }

    public function deleteBulk($document, $data) {
        $rows = $this->getRows($data);
        foreach($rows as $row) {
            $this->hdelete($document, $this->getTable(), $row[$this->getPrimaryKey()]);
        }
    }

}

?>