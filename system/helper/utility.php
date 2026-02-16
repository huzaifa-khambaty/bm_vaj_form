<?php

function getCSVFileData($filename, $delimiter) {
    $data = array();
    if (file_exists($filename)) {
        $fp = fopen($filename, 'r');

        while (!feof($fp)) {
            $line = fgets($fp, 2048);

            $columns = str_getcsv($line, $delimiter);
            
            $data[] = $columns;

        }

        fclose($fp);
    } 
    
    return $data;
}

function getFormatedDate($from_format, $str_datetime, $to_format) {
    $datetime = DateTime::createFromFormat($from_format, $str_datetime);
    return $datetime->format($to_format); 
}

function MySqlDate($str_date = '') {
    if($str_date == '') {
        $str_date = date(STD_DATE);
    }
    return getFormatedDate(STD_DATE, $str_date, MYSQL_DATE);
}

function stdDate($str_date = '') {
    if($str_date == '') {
        $str_date = date(MYSQL_DATE);
    }
    return getFormatedDate(MYSQL_DATE, $str_date, STD_DATE);
}

function MySqlDateTime($str_datetime) {
    return getFormatedDate(STD_DATETIME, $str_datetime, MYSQL_DATETIME);
}

function stdDateTime($str_datetime) {
    return getFormatedDate(MYSQL_DATETIME, $str_datetime, STD_DATETIME);
}

function objectToArray($d) {
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
         * Return array converted to object
         * Using __FUNCTION__ (Magic constant)
         * for recursive call
         */
        return array_map(__FUNCTION__, $d);
    } else {
        // Return array
        return $d;
    }
}

?>