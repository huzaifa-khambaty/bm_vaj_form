<?php

class ControllerCommonHome extends HController {

    public function index() {
        $this->load->language('common/home');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->data['heading_title'] = $this->language->get('heading_title');

        if (isset($this->session->data['error'])) {
            $this->data['error_warning'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } elseif (isset($this->session->data['warning'])) {
            $this->data['error_warning'] = $this->session->data['warning'];

            unset($this->session->data['warning']);
        } elseif (isset($this->session->data['error_warning'])) {
            $this->data['error_warning'] = $this->session->data['error_warning'];

            unset($this->session->data['error_warning']);
        } else {
            $this->data['error_warning'] = '';
        }
        
        // Check install directory exists
        if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
            $this->data['error_install'] = $this->language->get('error_install');
        } else {
            $this->data['error_install'] = '';
        }

        // Check image directory is writable
        $file = DIR_IMAGE . 'test';

        $handle = fopen($file, 'a+');

        fwrite($handle, '');

        fclose($handle);

        if (!file_exists($file)) {
            $this->data['error_image'] = sprintf($this->language->get('error_image') . DIR_IMAGE);
        } else {
            $this->data['error_image'] = '';

            unlink($file);
        }

        // Check image cache directory is writable
        $file = DIR_IMAGE . 'cache/test';

        $handle = fopen($file, 'a+');

        fwrite($handle, '');

        fclose($handle);

        if (!file_exists($file)) {
            $this->data['error_image_cache'] = sprintf($this->language->get('error_image_cache') . DIR_IMAGE . 'cache/');
        } else {
            $this->data['error_image_cache'] = '';

            unlink($file);
        }

        // Check cache directory is writable
        $file = DIR_CACHE . 'test';

        $handle = fopen($file, 'a+');

        fwrite($handle, '');

        fclose($handle);

        if (!file_exists($file)) {
            $this->data['error_cache'] = sprintf($this->language->get('error_image_cache') . DIR_CACHE);
        } else {
            $this->data['error_cache'] = '';

            unlink($file);
        }

        // Check download directory is writable
        $file = DIR_DOWNLOAD . 'test';

        $handle = fopen($file, 'a+');

        fwrite($handle, '');

        fclose($handle);

        if (!file_exists($file)) {
            $this->data['error_download'] = sprintf($this->language->get('error_download') . DIR_DOWNLOAD);
        } else {
            $this->data['error_download'] = '';

            unlink($file);
        }

        // Check logs directory is writable
        $file = DIR_LOGS . 'test';

        $handle = fopen($file, 'a+');

        fwrite($handle, '');

        fclose($handle);

        if (!file_exists($file)) {
            $this->data['errorlogs'] = sprintf($this->language->get('error_logs') . DIR_LOGS);
        } else {
            $this->data['error_logs'] = '';

            unlink($file);
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['token'] = $this->session->data['token'];


        if ($this->config->get('config_currency_auto')) {
            $this->load->model('localisation/currency');

            $this->model_localisation_currency->updateCurrencies();
        }

        $this->model['user_mohallah'] = $this->load->model('setup/user_mohallah');
        $arrUserMohallahs = $this->model['user_mohallah']->getArrays('mohallah_id', 'mohallah_id', array('user_id' => $this->user->getId()));

        $this->model['mohallah'] = $this->load->model('setup/mohallah');
        $mohallahs = $this->model['mohallah']->getRows();
        foreach($mohallahs as $mohallah) {
            if(in_array($mohallah['id'], $arrUserMohallahs)) {
                $this->data['mohallahs'][] = $mohallah;
            }
        }

        $this->template = 'common/home.tpl';
        $this->children = array(
            'common/header',
            'common/column_left',
            'common/footer',
        );

        $this->response->setOutput($this->render());
    }

    public function login() {
        $route = '';

        if (isset($this->request->get['route'])) {
            $part = explode('/', $this->request->get['route']);

            if (isset($part[0])) {
                $route .= $part[0];
            }

            if (isset($part[1])) {
                $route .= '/' . $part[1];
            }
        }

        $ignore = array(
            'common/login',
            'common/preset',
            'common/forgotten',
            'common/reset'
        );

        if (!$this->user->isLogged() && !in_array($route, $ignore)) {
            return $this->forward('common/login');
        }

        if (isset($this->request->get['route'])) {
            $ignore = array(
                'common/login',
                'common/preset',
                'common/logout',
                'common/forgotten',
                'common/reset',
                'error/not_found',
                'error/permission'
            );

            $config_ignore = array();

            if ($this->config->get('config_token_ignore')) {
                $config_ignore = unserialize($this->config->get('config_token_ignore'));
            }

            $ignore = array_merge($ignore, $config_ignore);

            if (!in_array($route, $ignore) && (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token']))) {
                return $this->forward('common/login');
            }
        } else {
            if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
                return $this->forward('common/login');
            }
        }
    }

    public function permission() {

        if (isset($this->request->get['route'])) {
            $route = '';

            $part = explode('/', $this->request->get['route']);

            if (isset($part[0])) {
                $route .= $part[0];
            }

            if (isset($part[1])) {
                $route .= '/' . $part[1];
            }

            $ignore = array(
                'common/home',
                'common/login',
//                'common/preset',
                'common/logout',
//                'common/forgotten',
//                'common/reset',
//                'user/user_permission',
                'error/not_found',
                'error/permission'
            );

            if (!in_array($route, $ignore) && !$this->user->hasPermission('view', $route)) {
                return $this->forward('error/permission');
            }
        }
    }

    public function getChartData() {
//        echo json_encode(array('status' => true));
//        exit;
        $this->model['user_mohallah'] = $this->load->model('setup/user_mohallah');
        $arrUserMohallahs = $this->model['user_mohallah']->getArrays('mohallah_id', 'mohallah_id', array('user_id' => $this->user->getId()));

        $this->model['home'] = $this->load->model('common/home');
        $mohallah_id = $this->request->post['mohallah_id'];
        $filter = array();
        if($mohallah_id) {
            $filter['mohallah_id'] = $mohallah_id;
        } else {
            $filter['mohallahs'] = implode(',',$arrUserMohallahs);
        }

        $dataPrinted = $this->model['home']->getFormPrintedData($filter);
        $dataTakmeen = $this->model['home']->getTakmeenData($filter);

        $chart_data_printed[] = array(
            'label' => 'Printed',
            'data' => 0 + $dataPrinted['printed'],
            'color' => '#77DD77'
        );
        $chart_data_printed[] = array(
            'label' => 'Balance',
            'data' => 0 + $dataPrinted['total'] - $dataPrinted['printed'],
            'color' => '#C23B22'
        );

        $chart_data_takmeen[] = array(
            'label' => 'Takmeen',
            'data' => 0 + $dataTakmeen['takmeen'],
            'color' => '#77DD77'
        );
        $chart_data_takmeen[] = array(
            'label' => 'Balance',
            'data' => 0 + $dataTakmeen['total'] - $dataTakmeen['takmeen'],
            'color' => '#C23B22'
        );

        $output = array(
            'success' => true,
            'dataPrinted' => json_encode($chart_data_printed),
            'dataTakmeen' => json_encode($chart_data_takmeen)
        );

        echo json_encode($output);
        exit;
    }

}

?>