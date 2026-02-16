<?php

class ControllerCommonHeader extends Controller {

    protected function index() {
        $this->data = $this->load->language('common/header');
        $this->data['title'] = $this->document->getTitle();

        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $this->data['base'] = HTTPS_BASE;
        } else {
            $this->data['base'] = HTTP_BASE;
        }

        $this->data['description'] = $this->document->getDescription();
        $this->data['keywords'] = $this->document->getKeywords();
        $this->data['links'] = $this->document->getLinks();
        $this->data['styles'] = $this->document->getStyles();
        $this->data['scripts'] = $this->document->getScripts();
        $this->data['lang'] = $this->language->get('code');
        $this->data['direction'] = $this->language->get('direction');

        $this->data['route'] = $this->request->get['route'];
        if (!$this->user->isLogged() || !isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
            $this->data['logged'] = '';

            $this->data['home'] = $this->url->link('common/login', '', 'SSL');
        } else {
            $this->data['action_user_profile'] = $this->url->link('user/user_profile', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['action_logout'] = $this->url->link('common/logout', 'token=' . $this->session->data['token'], 'SSL');
        }

        $this->template = 'common/header.tpl';
        
        $this->render();
    }

}

?>