<?php

class ControllerCommonColumnLeft extends Controller {

    public function index() {
        $this->data = $this->load->language('common/column_left');
        $permission = $this->user->getAllPermission();
        $arrPermission = [];
        foreach($permission as $index => $row) {
            $arrPermission[$index] = $row['view'];
        }
        $this->data['permission'] = $arrPermission;

        $this->data['action_home'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['action_user'] = $this->url->link('user/user', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['action_user_permission'] = $this->url->link('user/user_permission', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['action_form_printing'] = $this->url->link('setup/form_printing', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['action_takmeen'] = $this->url->link('setup/takmeen', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['action_takhmeen_report'] = $this->url->link('setup/takmeen_report', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['action_takhmeen_report_amount'] = $this->url->link('setup/takmeen_report_amount', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['action_comparison_report'] = $this->url->link('setup/comparison_report', 'token=' . $this->session->data['token'], 'SSL');

        $this->template = 'common/column_left.tpl';
        $this->render();
    }

}

?>