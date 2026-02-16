<?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
    <div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
    <div class="left"></div>
    <div class="right"></div>
    <div class="heading">
        <h1><?php echo $heading_title; ?></h1>
        <div class="buttons">
            <a href='javascript:void(0);' onclick="print_receipt();" class="button"><span><?php echo __('Print'); ?></span></a>
            <a href='javascript:void(0);' onclick="print_insert_receipt();" class="button"><span><?php echo __('Print & Insert'); ?></span></a>
            <a href='javascript:void(0);' onclick="save_receipt_and_list();" class="button button-blue"><span><?php echo __('Save & List'); ?></span></a>
            <a href='javascript:void(0);' onclick="save_receipt();" class="button button-pink"><span><?php echo __('Save'); ?></span></a>
            <a href='<?php echo $cancel; ?>' class="button"><span><?php echo __('Cancel'); ?></span></a>
        </div>
    </div>
    <div class="content">
        <table class="form" style="border: dashed thin #888">
            <tr>
                <td>
                    <table class="list" style="border: dashed thin #888">
                        <thead>
                            <tr>
                                <td><?php echo __('SF#'); ?></td>
                                <td><?php echo __('E-Jamaat#'); ?></td>
                                <td><?php echo __('Name'); ?></td>
                                <td><?php echo __('V. Amt'); ?></td>
                                <td><?php echo __('SF. Amt.'); ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($sfs as $sf): ?>
                            <tr>
                                <td><?php echo $sf['sf_no']; ?></td>
                                <td><?php echo $sf['ejamaat_no']; ?></td>
                                <td><?php echo $sf['momin']; ?></td>
                                <td><?php echo number_format($sf['amount_vajebaat']); ?></td>
                                <td><?php echo number_format($sf['amount_silafitra']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table class="list" style="border: dashed thin #888">
                        <thead>
                            <tr>
                                <td><?php echo __('SF#'); ?></td>
                                <td><?php echo __('V. Amt'); ?></td>
                                <td><?php echo __('SF. Amt.'); ?></td>
                                <td><?php echo __('Inst. No.'); ?></td>
                                <td><?php echo __('Inst. Date'); ?></td>
                                <td><?php echo __('Inst. Amt.'); ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($envelopes as $sf): ?>
                            <tr>
                                <td><?php echo $sf['sfno']; ?></td>
                                <td><?php echo number_format($sf['v_amount']); ?></td>
                                <td><?php echo number_format($sf['s_amount']); ?></td>
                                <td><?php echo $sf['instrument_no']; ?></td>
                                <td><?php echo $sf['instrument_date']; ?></td>
                                <td><?php echo number_format($sf['instrument_amount']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div class="heading">
        <div class="buttons">
            <a href='javascript:void(0);' id="print" onclick="print_receipt();" class="button"><span><?php echo __('Print'); ?></span></a>
            <a href='javascript:void(0);' id="print_insert" onclick="print_insert_receipt();" class="button"><span><?php echo __('Print & Insert'); ?></span></a>
            <a href='javascript:void(0);' id="save_only" onclick="save_receipt_and_list();" class="button button-blue"><span><?php echo __('Save & List'); ?></span></a>
            <a href='javascript:void(0);' onclick="save_receipt();" class="button button-pink"><span><?php echo __('Save'); ?></span></a>
            <a href='<?php echo $cancel; ?>' class="button"><span><?php echo __('Cancel'); ?></span></a>
        </div>
    </div>
</div>
<script type="text/javascript">
    function print_receipt() {
        window.open('<?php echo makeUrl('vajebaat/receipt/print_receipt') . '&barcode_no=' . $barcode_no; ?>', 'ReceiptPrint', 'width=600,height=600,scrollbars=yes,left=400');
        location = '<?php echo makeUrl('vajebaat/receipt'); ?>';
    }
    function print_insert_receipt() {
        window.open('<?php echo makeUrl('vajebaat/receipt/print_receipt') . '&barcode_no=' . $barcode_no; ?>', 'ReceiptPrint', 'width=600,height=600,scrollbars=yes,left=400');
        location = '<?php echo makeUrl('vajebaat/receipt/insert'); ?>';
    }
    function save_receipt_and_list() {
        //window.open('<?php echo makeUrl('vajebaat/receipt/print_receipt') . '&save_only=1&barcode_no=' . $this->request->get['barcode_no']; ?>', '', 'width=640,height=300,scrollbars=yes');
        location = '<?php echo makeUrl('vajebaat/receipt/print_receipt') . '&save_only=1&list_view=1&barcode_no=' . $this->request->get['barcode_no']; ?>';
    }
    function save_receipt() {
        //window.open('<?php echo makeUrl('vajebaat/receipt/print_receipt') . '&save_only=1&barcode_no=' . $this->request->get['barcode_no']; ?>', '', 'width=640,height=300,scrollbars=yes');
        location = '<?php echo makeUrl('vajebaat/receipt/print_receipt') . '&save_only=1&barcode_no=' . $this->request->get['barcode_no']; ?>';
    }
    $(document).ready(function() {
        if('<?php echo CURRENT_APPLICATION; ?>' == '<?php echo APP_BM; ?>') {
            $('#print_insert').focus();
        } else {
            //$('#save_only').focus();
            $('#print_insert').focus();
        }
    });
    $(document).keypress(function(e) {
        kcode = e.keyCode? e.keyCode : e.which;
        if(kcode==123) {
            window.open('<?php echo makeUrl('vajebaat/receipt/print_receipt') . '&barcode_no=' . $this->request->get['barcode_no']; ?>', '', 'width=640,height=300,scrollbars=yes');
            location = '<?php echo makeUrl('vajebaat/receipt/insert'); ?>';;
            e.preventDefault();
        }

    });
</script>