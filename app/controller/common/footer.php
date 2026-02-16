<?php
class ControllerCommonFooter extends Controller {
    protected function index() {
        $this->data = $this->load->language('common/footer');
        $this->data['token'] = $this->session->data['token'];
        $this->template = 'common/footer.tpl';
        $this->render();
    }
}
?>