<?php
include_once (DIR_SYSTEM ."library/tcpdf/tcpdf.php");
class ControllerSetupFormPrinting extends HController
{

    protected function getAlias()
    {
        return 'setup/form_printing';
    }

    protected function getPrimaryKey()
    {
        return 'form_printing_id';
    }

    public function index()
    {
        $this->init();

        $this->getForm();
    }

    protected function getList()
    {
        parent::getList();

        $this->response->setOutput($this->render());
    }

    protected function getForm()
    {
        parent::getForm();

        $this->model['user_mohallah'] = $this->load->model('setup/user_mohallah');
        $arrUserMohallahs = $this->model['user_mohallah']->getArrays('mohallah_id', 'mohallah_id', array('user_id' => $this->user->getId()));

        $this->model['mohallah'] = $this->load->model('setup/mohallah');
        $mohallahs = $this->model['mohallah']->getRows();
        $arrMohallahs = array();
        foreach ($mohallahs as $mohallah) {
            if (in_array($mohallah['id'], $arrUserMohallahs)) {
                $arrMohallahs[$mohallah['id']] = $mohallah['name'];
            }
        }

        $this->data['mohallahs'] = $arrMohallahs;
        $this->data['action_print_all'] = $this->url->link($this->getAlias() . '/printAll', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['action_print2_all'] = $this->url->link($this->getAlias() . '/print2All', 'token=' . $this->session->data['token'], 'SSL');

        $this->response->setOutput($this->render());
    }

    public function printAll() {
        $mids = explode(',',$this->request->get['eid']);
        $pdf = new PDF('P', 'pt', array(1240, 1754));
        foreach($mids as $momin_id) {
            $this->init();
            $hyear = CURRENT_YEAR;
            $row = $this->session->data['forms'][$momin_id];


            if (!file_exists(DIR_BARCODE_EJ . $row['ejamaat_no'] . '.jpeg')) {
                $this->barcode->displayDigit(false);
                $this->barcode->genBarCode($row['ejamaat_no'], 'jpeg', DIR_BARCODE_EJ . $row['ejamaat_no']);
            }
            $barcode_ej = DIR_BARCODE_EJ . $row['ejamaat_no'] . '.jpeg';

            if (!file_exists(DIR_BARCODE_SF . $row['sf_no'] . '.jpeg')) {
                $this->barcode->displayDigit(false);
                $this->barcode->genBarCode($row['sf_no'], 'jpeg', DIR_BARCODE_SF . $row['sf_no']);
            }
            $barcode_sf = DIR_BARCODE_SF . $row['sf_no'] . '.jpeg';

            $row['barcode_ej'] = $barcode_ej;
            $row['barcode_sf'] = $barcode_sf;

            $pdf->SetFont('Arial', '', 14);
            $pdf->AddPage();
            $pdf->Image('image/v5notext.png', 0, 0, 1240, 1754);
            //$pdf->Body(array('hyear' => $hyear, 'data' => $row));
            $pdf->Body(array('hYear' => $hyear, 'eYear' => date('Y'), 'data' => $row));

            $this->model['form_printing_log'] = $this->load->model('setup/form_printing_log');
            $data = array(
                'momin_id' => $row['ejamaat_no'],
            );
            $this->model['form_printing_log']->add($this->getAlias(), $data);
        }
        $pdf->Output();

    }

    public function print2All() {
        $mids = explode(',',$this->request->get['eid']);
        $new_mids = array_chunk($mids, 2);

        //d($new_mids, true);
        $pdf = new PDF2('P', 'pt', 'A5', true, 'UTF-8', false);
        $lg = Array();
        $lg['a_meta_charset'] = 'UTF-8';
        $lg['a_meta_dir'] = 'rtl';
        $lg['a_meta_language'] = 'fa';
        $lg['w_page'] = 'page';

        // set some language-dependent strings (optional)
        $pdf->setLanguageArray($lg);

        $pdf->SetPrintFooter(false);
        $pdf->SetPrintHeader(false);
        $pdf->setHeaderMargin(0);
        $pdf->setFooterMargin(0);
        $pdf->SetMargins(20,10,20);
        //$fontname = TCPDF_FONTS::addTTFfont(DIR_SYSTEM . '/library/tcpdf/fonts/fatimi5.ttf', 'TrueTypeUnicode', '', 96);

        // use the font
        //$pdf->SetFont($fontname, '', 48, '', false);
        $this->init();
        $hyear = CURRENT_YEAR;
        foreach($new_mids as $records) {
            $pdf->AddPage();
            foreach($records as $index => $its) {
                $data = $this->session->data['forms'][$its];
                $apiReceipt = NEW APIReceipt(PROXY_SERVER);
                $filter  = array('sf_no' => $data['sfno']);
                $obj = $apiReceipt->getVReceipts($filter);
                $response = objectToArray(json_decode($obj));
                if($response['status'] == 1) {
                    $data['previous_amount'] = $response['data'][0]['amount'];
                }
                //d($this->session->data, true);
//            if (!file_exists(DIR_BARCODE_EJ . $data['ejamaat_no'] . '.jpeg')) {
//                $this->barcode->displayDigit(false);
//                $this->barcode->genBarCode($data['ejamaat_no'], 'jpeg', DIR_BARCODE_EJ . $data['ejamaat_no']);
//            }
//            $barcode_ej = DIR_BARCODE_EJ . $data['ejamaat_no'] . '.jpeg';
//
//            if (!file_exists(DIR_BARCODE_SF . $data['sf_no'] . '.jpeg')) {
//                $this->barcode->displayDigit(false);
//                $this->barcode->genBarCode($data['sf_no'], 'jpeg', DIR_BARCODE_SF . $data['sf_no']);
//            }
//            $barcode_sf = DIR_BARCODE_SF . $data['sf_no'] . '.jpeg';
//
//            //d($row, true);
//            $data['barcode_ej'] = $barcode_ej;
//            $data['barcode_sf'] = $barcode_sf;

//            $pdf->SetLineStyle( array( 'width' => 1, 'color' => array(0,0,0)));
//            $pdf->Rect(10, 10, $pdf->getPageWidth()-20, $pdf->getPageHeight()-20);
//            $pdf->SetLineStyle( array( 'width' => 0.25, 'color' => array(0,0,0)));
                // set font
                //$fontname = TCPDF_FONTS::addTTFfont(DIR_SYSTEM . '/library/tcpdf/fonts/alfatemi152.ttf', 'TrueTypeUnicode', '', 96);

                // use the font
                $pdf->SetFont('alfatemi152', '', 24, '', false);
                //$pdf->SetFont('fatimi5', '', 24, '', false);
                //$pdf->SetFont('saifee', '', 24, '', false);
                //$pdf->SetFont('aefurat', '', 24, '', false);
                //$pdf->SetFont('aefurat', '', 48);

                // print newline
                //$pdf->Ln(5);
                //$pdf->Cell(0, 15,'الحقوق الواجبات',0,1,'C');
                $pdf->SetFont('alfatemi152', '', 20, '', false);
                $pdf->Cell(0, 5,'شهر الله المعظم 1442هـ',0,1,'C');

                $pdf->setRTL(true);
                $pdf->Ln(15);
                $pdf->SetFontSize(15);
                $pdf->Cell(100, 20,'سنة 1441هـ ما',0,0,'C');
                $pdf->SetFontSize(32);
                $pdf->Cell(160, 20,number_format($data['previous_amount']),1,0,'C');
                $pdf->SetFontSize(16);
                $pdf->Cell(100, 20,'عرض كيدا',0,0,'R');

                $pdf->Ln(50);
                $pdf->SetFontSize(15);
                $pdf->Cell(100, 20,'سنة 1442هـ ما',0,0,'C');
                $pdf->SetFontSize(32);
                $pdf->Cell(160, 20,'',1,0,'C');
                $pdf->SetFontSize(16);
                $pdf->Cell(100, 20,'ني تخمين كيدى ؛',0,0,'R');

                // print newline
                $pdf->Ln(60);

                // set LTR direction for english translation
                $pdf->setRTL(false);

                //$display_name = $data['name_ar'] !=""?iconv("UTF-8",mb_detect_encoding($data['name_ar']),$data['name_ar']):$data['full_name'];
                $display_name = $data['name_ar'] !=""?iconv("UTF-8",mb_detect_encoding($data['name_ar']),$data['name_ar']):$data['full_name'];
                // set font
                $pdf->SetFontSize(15);
                $y = $pdf->GetY();
                $pdf->MultiCell(260, 24,$display_name,'B','R',false,0,'',$y);
                $x = $pdf->GetX();
                $pdf->SetFontSize(16);
                $pdf->MultiCell(100, 24,'عرض كرنار نو نام',0,'R',false,0,$x,$y);
                $pdf->Ln(60);

                // set font
                //$pdf->SetFont('times', '', 10);
                $pdf->SetFontSize(16);

                //Print Amount
                $pdf->Cell(180, 12,'','B',0,'L');
                //$pdf->Cell(180, 12,$data['mohallah'],'B',0,'L');
                $pdf->Cell(40, 12,'موضع',0,0,'R');
                $pdf->SetFont('helvetica', '', 12, '', false);
                $pdf->Cell(20, 12,'',0,0,'R');
                $pdf->Cell(50, 12,'ITS No.',1,0,'R');
                $pdf->Cell(90, 12,$data['ejamaat_no'].' ('.$data['sfno'].')','B',0,'L');
                //if($index==0) {
                    $pdf->Ln(28);
                    $pdf->Cell(0, 12,'','B',1);
                    $pdf->Ln(20);
                //}

//            // print newline
//            $pdf->Ln(40);
//
//            // set font
//            //$fontname = TCPDF_FONTS::addTTFfont(DIR_SYSTEM . '/library/tcpdf/fonts/fatimi5.ttf', 'TrueTypeUnicode', '', 96);
//            // use the font
//            $pdf->SetFont($fontname, '', 84, '', false);
//
//            $pdf->SetLineStyle( array('width' => 0.5, 'cap' => 'round', 'join' => 'round', 'dash' => '0', 'color' => array(0,0,0)));
//            //Print Amount
//            $pdf->Cell(0, 15,$data['amount_vajebaat'],1,1,'C');
//            $pdf->SetLineStyle( array( 'width' => 0.25, 'color' => array(0,0,0)));


//            // set font
//            $pdf->SetFont('times', '', 10);
//            $pdf->SetFontSize(10);
//
//            $pdf->ln(58);
//            //Print Amount
//            $pdf->Cell(10);
//            $pdf->Cell(40, 5,$data['barcode_no'],0,0,'C');
//            $pdf->Cell(20);
//            $pdf->Cell(40, 5,$data['bethak_token'],0,0,'C');
//            $pdf->Cell(20);
//            $pdf->Cell(40, 5,$data['ejamaat_no'],0,0,'C');
//
//            $pdf->ln(3);
//            $pdf->Cell(10);
//            if($data['amount_cash'] > 0) {
//                $pdf->Cell(40, 1,'C',0,0,'C');
//            } else {
//                $pdf->Cell(40, 1,'',0,0,'C');
//            }
//            $pdf->Cell(80);
//            if($data['amount_cheque'] > 0) {
//                $pdf->Cell(40, 1,'Q',0,0,'C');
//            } else {
//                $pdf->Cell(40, 1,'',0,0,'C');
//            }

                // force print dialog
//            $js = 'print(true);';
//            $js .= 'onfocus=function(){ window.close();}';
//
//            // set javascript
//            $pdf->IncludeJS($js);
                //Close and output PDF document

                $this->model['form_printing_log'] = $this->load->model('setup/form_printing_log');
                $idata = array(
                    'momin_id' => $data['ejamaat_no'],
                );
                $this->model['form_printing_log']->add($this->getAlias(), $idata);
            }
        }
        //$pdf->Output();
        $pdf->Output('BethakCard.pdf', 'I');
        exit;
    }

    public function getAjaxLists()
    {

        $this->load->language($this->getAlias());
        $this->model[$this->getAlias()] = $this->load->model($this->getAlias());
        $data = array();
        $aColumns = array('sf_no', 'ejamaat_no', 'full_name', 'mohallah', 'previous_amount', 'last_amount', 'action');

        /*
         * Paging
         */
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $data['criteria']['start'] = $_GET['iDisplayStart'];
            $data['criteria']['limit'] = $_GET['iDisplayLength'];
        }

        /*
         * Ordering
         */
        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = " ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= "`" . $aColumns[intval($_GET['iSortCol_' . $i])] . "` " .
                        ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == " ORDER BY") {
                $sOrder = "";
            }
            $data['criteria']['orderby'] = $sOrder;
        }


        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $sWhere = "";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch'] != '') {
                    if ($aColumns[$i] == 'sf_no') {
                        $sWhere .= "`" . $aColumns[$i] . "` ='" . mysql_real_escape_string($_GET['sSearch']) . "' OR ";
                    } elseif ($aColumns[$i] == 'ejamaat_no') {
                        $sWhere .= "`" . $aColumns[$i] . "` ='" . mysql_real_escape_string($_GET['sSearch']) . "' OR ";
                    } else {
                        $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                    }
                }
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
            }
        }

        if ($sWhere != "") {
            $data['filter']['RAW'] = substr($sWhere, 5, strlen($sWhere) - 5);
            $results = $this->model[$this->getAlias()]->getLists($data);
        } else {
            $results = array(
                'total' => 0,
                'table_total' => 0,
                'lists' => array()
            );
        }

        //d($data);
        $iFilteredTotal = $results['total'];
        $iTotal = $results['table_total'];


        /*
         * Output
         */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($results['lists'] as $aRow) {
            $row = array();
            $actions = array();

            $actions[] = array(
                'text' => $this->language->get('text_print'),
                'href' => $this->url->link($this->getAlias() . '/pdf', 'token=' . $this->session->data['token'] . '&' . $this->getPrimaryKey() . '=' . $aRow[$this->getPrimaryKey()], 'SSL'),
                'class' => 'fa fa-print'
            );

            $actions[] = array(
                'text' => $this->language->get('text_print'),
                'href' => $this->url->link($this->getAlias() . '/pdf2', 'token=' . $this->session->data['token'] . '&' . $this->getPrimaryKey() . '=' . $aRow[$this->getPrimaryKey()], 'SSL'),
                'class' => 'fa fa-print'
            );

            $strAction = '';
            foreach ($actions as $action) {
                $strAction .= '<a href="' . $action['href'] . '" title="' . $action['text'] . '" ' . (isset($action['click']) ? 'onClick="' . $action['click'] . '"' : '') . '>';
                if (isset($action['class'])) {
                    $strAction .= '<span class="' . $action['class'] . '"></span>';
                } else {
                    $strAction .= $action['text'];
                }
                $strAction .= '</a>&nbsp;';
            }

            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == 'action') {
                    $row[] = $strAction;
                } else {
                    $row[] = $aRow[$aColumns[$i]];
                }
            }
            $output['aaData'][] = $row;
        }

        echo json_encode($output);
    }

    public function pdf()
    {
        $this->init();
//        $form_printing_id = $this->request->get['form_printing_id'];
//
//        $this->model['form_printing'] = $this->load->model('setup/form_printing');
//        $row = $this->model['form_printing']->getRow(array('form_printing_id' => $form_printing_id));
//        $hyear = $this->model['form_printing']->getCurrentYear();

        $this->model['momin'] = $this->load->model('setup/momin');
        $family_count = $this->model['momin']->getFamilyCount(array('momin_id' => $this->request->get['momin_id']));

        $hyear = CURRENT_YEAR;
        $row = $this->session->data['forms'][$this->request->get['momin_id']];

        if (!file_exists(DIR_BARCODE_EJ . $row['ejamaat_no'] . '.jpeg')) {
            $this->barcode->displayDigit(false);
            $this->barcode->genBarCode($row['ejamaat_no'], 'jpeg', DIR_BARCODE_EJ . $row['ejamaat_no']);
        }
        $barcode_ej = DIR_BARCODE_EJ . $row['ejamaat_no'] . '.jpeg';

        if (!file_exists(DIR_BARCODE_SF . $row['sf_no'] . '.jpeg')) {
            $this->barcode->displayDigit(false);
            $this->barcode->genBarCode($row['sf_no'], 'jpeg', DIR_BARCODE_SF . $row['sf_no']);
        }
        $barcode_sf = DIR_BARCODE_SF . $row['sf_no'] . '.jpeg';

        $row['barcode_ej'] = $barcode_ej;
        $row['barcode_sf'] = $barcode_sf;

        $pdf = new PDF('P', 'pt', array(1240, 1620));
        $pdf->SetFont('Arial', '', 14);
        $pdf->AddPage();
        $pdf->Image('image/v5notext.png', 0, 0, 1240, 1754);
        $pdf->Body(array('hYear' => $hyear, 'eYear' => date('Y'), 'data' => $row));
        $pdf->Output();

        $this->model['form_printing_log'] = $this->load->model('setup/form_printing_log');
        $data = array(
            'momin_id' => $row['ejamaat_no'],
        );
        $this->model['form_printing_log']->add($this->getAlias(), $data);
        exit;
    }

    public function getRows()
    {
        $this->init();
        unset($this->session->data['forms']);
        $post = $this->request->post;
        $this->model['user_mohallah'] = $this->load->model('setup/user_mohallah');
        $arrMohallahAccess = $this->model['user_mohallah']->getArrays('mohallah_id', 'mohallah_id', array('user_id' => $this->user->getId()));
        $post['mohallahs'] = implode(',', $arrMohallahAccess);
        $data = array(
            'current_year' => CURRENT_YEAR,
            'post' => $post
        );
////        echo json_encode($data);
////        exit;
//        $apiFormPrinting = new APIFormPrinting(PROXY_SERVER);
//        $json_rows = $apiFormPrinting->getRows($data);
//        $object_rows = json_decode($json_rows);
//        $response = objectToArray($object_rows);
////        echo json_encode($response);
////        exit;
        $this->model['momin'] = $this->load->model('setup/momin');
        $filter = $post;
        $rows = $this->model['momin']->getMomins($filter);
//        d(array($filter,$rows));
        $response = array(
            'status' => true,
            'rows' => $rows,
            'post' => $post
        );
        $json = array();
        if ($response['status']) {
            $rows = array();
            foreach ($response['rows'] as $row) {
                if (in_array($row['mohallah_id'], $arrMohallahAccess)) {
                    $this->session->data['forms'][$row['ejamaat_no']] = $row;
                }
            }
            if ($this->session->data['forms']) {
                $action_print_all = $this->url->link($this->getAlias() . '/printAll', 'token=' . $this->session->data['token'], 'SSL');
                $html = '';
                $html .= ' <table id="tblFormData" class="table table-striped table-bordered table-hover" align="center">';
                $html .= ' <thead>';
                $html .= ' <tr>';
                $html .= ' <td align="center">&nbsp;</td>';
                $html .= ' <td align="center">Sr.</td>';
                $html .= ' <td align="center">' . $this->language->get('column_sf_no') . '</td>';
                $html .= ' <td align="center">' . $this->language->get('column_ejamaat_no') . '</td>';
                $html .= ' <td align="center">' . $this->language->get('column_momin') . '</td>';
                $html .= ' <td align="center">' . $this->language->get('column_mohallah') . '</td>';
//                $html .= ' <td align="center">' . (CURRENT_YEAR - 1) . '</td>';
//                $html .= ' <td align="center">' . (CURRENT_YEAR - 2) . '</td>';
//                $html .= ' <td align="center">' . (CURRENT_YEAR - 3) . '</td>';
                $html .= ' <td align="right">';
                $html .= ' <a class="btn btn-default" href="javascript:void(0);" title="Print" onclick="printAll();" >Print</a>';
                //$html .= ' <a class="btn btn-default" href="javascript:void(0);" title="Print" onclick="print2All();" >Card</a>';
                $html .= ' </td>';
                $html .= ' </tr>';
                $html .= ' </thead>';
                $html .= ' <tbody>';
                $this->model['form_printing_log'] = $this->load->model('setup/form_printing_log');
                $row_no = 1;
                foreach ($this->session->data['forms'] as $row) {
                    $printed = $this->model['form_printing_log']->getRow(array('momin_id' => $row['ejamaat_no']));
                    $permission = $this->user->hasPermission('reprint', 'setup/form_printing');

                    $url = $this->url->link($this->getAlias() . '/pdf', 'token=' . $this->session->data['token'] . '&momin_id=' . $row['ejamaat_no'], 'SSL');
                    $html .= ' <tr row_id="' . $row['ejamaat_no'] . '">';
                    $html .= ' <td align="right"><input type="checkbox" name="print_' . $row_no . '" value="" /></td>';
                    $html .= ' <td align="right">' . $row_no . '</td>';
                    $html .= ' <td align="left">' . $row['sf_no'] . '</td>';
                    $html .= ' <td align="left">' . $row['ejamaat_no'] . '</td>';
                    $html .= ' <td align="left">' . $row['momin'] . '</td>';
                    $html .= ' <td align="left">' . $row['mohallah'] . '</td>';
//                    $html .= ' <td align="right">' . $row['amount1'] . '</td>';
//                    $html .= ' <td align="right">' . $row['amount2'] . '</td>';
//                    $html .= ' <td align="right">' . $row['amount3'] . '</td>';
                    if (count($printed) == 0 || $permission) {
                        $html .= ' <td align="right"><a id="' . $row['ejamaat_no'] . '" target="_blank" href="' . $url . '" title="' . $row['momin'] . '"><span class="fa fa-print"></span></a></td>';
                    } else {
                        $html .= ' <td align="right">&nbsp;</td>';
                    }
                    $html .= ' </tr>';
                    $row_no++;
                }
                $html .= ' </tbody>';
                $html .= ' </table>';

                $json = array(
                    'status' => true,
                    'html' => $html,
                    'response' => $response
                );
            } else {
                $json = array(
                    'status' => false,
                    'error' => 'Error: No Records Found',
                    'response' => $response
                );
            }
        } else {
            $json = array(
                'status' => false,
                'response' => $response,
                'error' => 'Error: Retrieving Data. Contact System Administrator'
            );
        }
        echo json_encode($json);
        exit;
    }
}

class PDF extends FPDF
{

    var $angle = 0;

    function Rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1)
            $x = $this->x;
        if ($y == -1)
            $y = $this->y;
        if ($this->angle != 0)
            $this->_out('Q');
        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    function _endpage()
    {
        if ($this->angle != 0) {
            $this->angle = 0;
            $this->_out('Q');
        }
        parent::_endpage();
    }

    function RotatedText($x, $y, $txt, $angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }

    function RotatedImage($file, $x, $y, $w, $h, $angle)
    {
        //Image rotated around its upper-left corner
        $this->Rotate($angle, $x, $y);
        $this->Image($file, $x, $y, $w, $h);
        $this->Rotate(0);
    }

// Page header
    function Header()
    {

    }

// Page footer
    function Footer()
    {
// Position at 1.5 cm from bottom
        $this->SetY(-15);
//// Arial italic 8
//        $this->SetFont('Arial','I',8);
//// Page number
//        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

    function Body($data)
    {
        $row = $data['data'];

        $this->Image($row['barcode_sf'], 640, 60, 350, 100);

        $this->ln(110);
        $this->SetFont('times', 'B', 14);
        $this->Cell(100);
        $this->Cell(90, 30, $data['hYear'], 0, 0, 'C');
        $this->SetFont('times', 'B', 40);
        $this->Cell(480);
        $this->Cell(230, 30, $row['sf_no'], 0, 0, 'C');

        $this->ln(30);
        $this->SetFont('times', 'B', 14);
        $this->Cell(100);
        $this->Cell(90, 30, $data['eYear'], 0, 0, 'C');

        $this->ln(50);
        $this->SetFont('times', 'B', 20);
        $this->Cell(580);
        $this->Cell(250, 30, $row['mohallah'], 0, 0, 'R');

        $this->RotatedImage($row['barcode_ej'], 1100, 800, 225, 50, 90);
        $this->RotatedText(1160, 730, $row['ejamaat_no'], 90);

        $this->SetFont('times', 'B', 20);
        $this->ln(270);
        $this->Cell(700);
        $this->Cell(30, 30, '', 0, 0, 'L');

        $this->SetFont('times', 'B', 20);
        $this->ln(58);
        $this->Cell(700);
        $this->Cell(30, 30, '', 0, 0, 'L');

        $this->SetFont('times', 'B', 20);
        $this->ln(58);
        $this->Cell(700);
        $this->Cell(30, 30, '', 0, 0, 'L');

        $this->SetFont('times', 'B', 20);
        $this->ln(58);
        $this->Cell(700);
        $this->Cell(30, 30, '', 0, 0, 'L');

        $this->SetFont('times', 'B', 20);
        $this->ln(58);
        $this->Cell(700);
        $this->Cell(30, 30, '', 0, 0, 'L');

        $this->SetFont('times', 'B', 16);
        $this->ln(518);
        $this->Cell(40);
        $this->Cell(380, 30, $row['momin'], 0, 0, 'R');

        $this->SetFont('times', 'B', 20);
        $this->ln(45);
        $this->Cell(125);
        $this->Cell(300, 30, $row['ejamaat_no'], 0, 0, 'L');

        $this->SetFont('times', 'B', 16);
        $this->ln(160);
//        $this->Cell(140,20,'FOR OFFICE USE','B');
//
//        $this->SetFont('times', 'B', 16);
//        $this->ln(30);
        $this->Cell(60,20,'Note:');
        $this->SetFont('times', 'B', 16);
        $this->Cell(370, 20, '1. Amount to be given vide Pay Order in the name of ', 0, 0, 'L');
        $this->Cell(165, 20, 'DAWAT-E-HADIYAH', 'B', 0, 'L');
        $this->Cell(300, 20, ' along with the form.', 0, 0, 'L');

        $this->ln(20);
        $this->Cell(60,20,'');
        $this->Cell(300, 20, '2. To process pay order, Dawat-e-Hadiyah NTN # 0787291-7', 0, 0, 'L');

        $this->SetFont('times', '', 16);
//        $this->ln(20);
//        $this->Cell(60,20,'');
//        $this->Cell(300, 20, '3. Collection will be done by Sector/Zone musaid', 0, 0, 'L');

        $this->ln(20);
        $this->Cell(60,20,'');
        $this->Cell(300, 20, '3. No Cash Transactions', 0, 0, 'L');

        $this->ln(20);
        $this->Cell(60,20,'');
        $this->Cell(300, 20, '4. Signature on Form is mandatory along with instrument details.', 0, 0, 'L');
    }
}

class PDF2 extends TCPDF
{

// Page header
    function Header()
    {

    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
//        $this->SetY(-15);
        //// Arial italic 8
        //        $this->SetFont('Arial','I',8);
        //// Page number
        //        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

}
?>