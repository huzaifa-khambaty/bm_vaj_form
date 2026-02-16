<?php
include_once (DIR_SYSTEM ."library/tcpdf/tcpdf.php");

class ControllerSetupTakmeenReportAmount extends HController {

    protected function getAlias() {
        return 'setup/takmeen_report_amount';
    }

    protected function getPrimaryKey() {
        return 'takmeen_id';
    }

    public function index()
    {
        $this->init();
        $this->getForm();
    }

    protected function getForm() {
        parent::getForm();

        $this->model['user_mohallah'] = $this->load->model('setup/user_mohallah');
        $arrUserMohallahs = $this->model['user_mohallah']->getArrays('mohallah_id', 'mohallah_id', array('user_id' => $this->user->getId()));

        $this->model['mohallah'] = $this->load->model('setup/mohallah');
        $mohallahs = $this->model['mohallah']->getRows([],['name']);
        $arrMohallahs = array();
        foreach ($mohallahs as $mohallah) {
            if (in_array($mohallah['id'], $arrUserMohallahs)) {
                $arrMohallahs[$mohallah['id']] = $mohallah['name'];
            }
        }

        $this->data['mohallahs'] = $arrMohallahs;

        $this->data['from_date'] = stdDate();
        $this->data['to_date'] = stdDate();

        $this->data['href_get_mohallah_users'] = $this->url->link($this->getAlias() . '/getMohallahUsers', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['href_print_report'] = $this->url->link($this->getAlias() . '/printReport', 'token=' . $this->session->data['token'], 'SSL');

        $this->template = $this->getAlias() . '.tpl';
        $this->response->setOutput($this->render());
    }

    public function getMohallahUsers() {
        $this->model['takmeen_report'] = $this->load->model('setup/takmeen_report_amount');
        $mohallah_id = $this->request->post['mohallah_id'];

        $rows = $this->model['takmeen_report']->getMohallahUsers($mohallah_id);
        $html = '<option value="">&nbsp;</option>'.PHP_EOL;
        foreach($rows as $row) {
            $html .= '<option value="'.$row['user_id'].'">'.$row['username'].' - '.$row['firstname'].' '.$row['lastname'].'</option>'.PHP_EOL;
        }

        $json = ['success'=>true, 'html' => $html];
        echo json_encode($json);
    }

    public function printReport() {
        $filter = $this->request->get;
        $this->model['takmeen_report'] = $this->load->model('setup/takmeen_report_amount');
        $arrFilter = [];
        $arrFilter['Date'] = "Date: ".$filter['from_date']." - ".$filter['to_date'];
        $arrWhere = [];
        $arrWhere[] = "`created_at` >= '".MySqlDate($filter['from_date'])." 00:00:00'";
        $arrWhere[] = "`created_at` <= '".MySqlDate($filter['to_date'])." 23:59:59'";
        if($filter['from_amount'] && $filter['to_amount']) {
            $arrWhere[] = "`amount` >= '".$filter['from_amount']."'";
            $arrWhere[] = "`amount` <= '".$filter['to_amount']."'";
            $arrFilter['Amount'] = "Amount: ".number_format($filter['from_amount'])." - ".number_format($filter['to_amount']);
        } elseif($filter['from_amount']) {
            $arrWhere[] = "`amount` >= '".$filter['from_amount']."'";
            $arrFilter['Amount'] = "Amount: GT ".number_format($filter['from_amount']);
        } elseif($filter['to_amount']) {
            $arrWhere[] = "`amount` <= '".$filter['to_amount']."'";
            $arrFilter['Amount'] = "Amount: LT ".number_format($filter['to_amount']);
        }
        if($filter['mohallah_id']) {
            $this->model['mohallah'] = $this->load->model('setup/mohallah');
            $mohallah = $this->model['mohallah']->getRow(['id' => $filter['mohallah_id']]);

            $arrWhere[] = "`mohallah_id` = '".$filter['mohallah_id']."'";
            $arrFilter['Mohallah'] = "Mohallah: ".$mohallah['name'];
        }
        if($filter['user_id']) {
            $this->model['user'] = $this->load->model('user/user');
            $user = $this->model['user']->getRow(['user_id' => $filter['user_id']]);

            $arrWhere[] = "`created_by_id` = '".$filter['user_id']."'";
            $arrFilter['User'] = "User: ".$user['username'].' - '.$user['firstname'].' '.$user['lastname'];
        }

        $where = implode(" AND ", $arrWhere);
        $rows = $this->model['takmeen_report']->getRows($where,[$filter['sort_order']]);
        $pdf = new PDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        $pdf->SetMargins(PDF_MARGIN_LEFT,40, PDF_MARGIN_RIGHT);
        $pdf->setHeaderMargin(PDF_MARGIN_HEADER);

        $pdf->data['filter'] = $arrFilter;
        $pdf->AddPage();
        $pdf->SetFont('helvetica','',8);
        $sr=0;
        $total_amount = 0;
        foreach($rows as $row) {
            $sr++;
            $total_amount += $row['amount'];

            $pdf->Cell(10,5,$sr,1,0,'C');
            $pdf->Cell(20,5,date('d-m-Y',strtotime($row['created_at'])),1,0,'C');
            $pdf->Cell(20,5,$row['ejamaat_no'],1,0,'C');
            $pdf->Cell(15,5,$row['sf_no'],1,0,'C');
            $pdf->Cell(100,5,$row['full_name'],1,0,'L');
            $pdf->Cell(20,5,number_format($row['amount']),1,0,'R');
            $pdf->ln(5);
        }
        $pdf->SetFont('helvetica','B',8);
        $pdf->Cell(165,5,'Total Amount: ',1,0,'R');
        $pdf->Cell(20,5,number_format($total_amount),1,0,'R');
        $pdf->Output('TakhmeenReport.pdf', 'I');
        //d([$filter, $rows], true);
    }


}

class PDF extends TCPDF
{
    public $data;
    // Page header
    function Header()
    {
        $this->SetFont('helvetica','',16);
        $this->Cell(0,10,'Takhmeen Report',0,0,'C');
        $this->ln(10);
        $this->SetFont('helvetica','',8);
        $this->Cell(0,5,$this->data['filter']['Date'],0,0,'L');
        $this->ln(5);
        $this->Cell(0,5,$this->data['filter']['Amount'],0,0,'L');
        $this->ln(5);
        $this->Cell(0,5,$this->data['filter']['Mohallah'],0,0,'L');
        $this->ln(5);
        $this->Cell(0,5,$this->data['filter']['User'],0,0,'L');
//        $this->ln(5);
//        $this->Cell(180,5,'',1,0,'L');
        $this->ln(5);
        $this->Cell(10,5,'Sr.',1,0,'C');
        $this->Cell(20,5,'Date',1,0,'C');
        $this->Cell(20,5,'ITS#',1,0,'C');
        $this->Cell(15,5,'SF#',1,0,'C');
        $this->Cell(100,5,'Name',1,0,'C');
        $this->Cell(20,5,'Takhmeen',1,0,'C');
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('helvetica','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

}

?>