<?php

class ModelSetupUserMohallah extends HModel {

    protected function getAlias() {
        return 'setup/user_mohallah';
    }

    protected function getTable() {
        return 'user_mohallah';
    }

    protected function getPrimaryKey() {
        return 'user_mohallah_id';
    }

}

?>