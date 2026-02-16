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
            <?php if($user_group == 1 || $user_group==2 || $user_group==9): ?>
            <a href ="<?php echo $insert; ?>" class="button button-blue"><span><?php echo $button_insert; ?></span></a>
            <?php endif; ?>
        </div>
    </div>
    <div class="content">
        <div style="">
            <table width="100%">
                <tr>
                    <td><?php echo __('Envelope No.'); ?></td>
                    <td><input type="text" name="barcode_no" id="barcode_no" tabindex="1" /></td>
                    <td><?php echo __('Silafitra No.'); ?></td>
                    <td><input type="text" name="sf_no" id="sf_no" tabindex="2" /></td>
                    <td><?php echo __('E-jamaat No.'); ?></td>
                    <td><input type="text" name="ejamaat_no" id="ejamaat_no" tabindex="3" /></td>
                    <td><a href="javascript:void(0);" onclick="fnSubmit();" class="button"><span>View</span></a></td>
                </tr>
            </table>
        </div>
        <table class="list">
            <thead>
                <tr>
                    <td class="left" width="7%"><?php echo CHtml::activeLabelLink($model, 'date', $this->getAlias(), $sort, $order) ?></td>
                    <td class="left" width="8%"><?php echo CHtml::activeLabelLink($model, 'barcode_no', $this->getAlias(), $sort, $order) ?></td>
                    <td class="left" width="10%"><?php echo CHtml::activeLabelLink($model, 'receipt_no', $this->getAlias(), $sort, $order) ?></td>
                    <td class="left" width="5%"><?php echo CHtml::activeLabelLink($model, 'mode_of_payment', $this->getAlias(), $sort, $order) ?></td>
                    <td class="left" width="5%"><?php echo CHtml::activeLabelLink($model, 'receipt_type', $this->getAlias(), $sort, $order) ?></td>
                    <td class="left" width="5%"><?php echo CHtml::activeLabelLink($model, 'sfno', $this->getAlias(), $sort, $order) ?></td>
                    <td class="left" width="5%"><?php echo CHtml::activeLabelLink($model, 'ejamaaat_no', $this->getAlias(), $sort, $order) ?></td>
                    <td class="left" width="35%"><?php echo CHtml::activeLabelLink($model, 'name', $this->getAlias(), $sort, $order) ?></td>
                    <td class="left" width="10%"><?php echo CHtml::activeLabelLink($model, 'created_by_id', $this->getAlias(), $sort, $order) ?></td>
                    <td class="right" width="10%">
                        <a target="_blank" href='<?php echo makeUrl('vajebaat/receipt/print_pending'); ?>' id="print_pending"  class="button button-blue"><span><?php echo __('Print Pending'); ?></span></a>
                        <?php echo $column_action; ?>
                    </td>
                </tr>
            </thead>
            <tbody>

                <tr class="filter">
                    <td><?php echo CHtml::filterLikeTextField('r.date', $filter, array('size' => 40)) ?></td>
                    <td><?php echo CHtml::filterLikeTextField('r.barcode_no', $filter, array('size' => 40)) ?></td>
                    <td><?php echo CHtml::filterLikeTextField('r.receipt_identity', $filter, array('size' => 40)) ?></td>
                    <td><?php echo CHtml::filterLikeTextField('r.mode_of_payment', $filter, array('size' => 40)) ?></td>
                    <td><?php echo CHtml::filterLikeTextField('r.receipt_type', $filter, array('size' => 40)) ?></td>
                    <td><?php echo CHtml::filterLikeTextField('r.sf_no', $filter, array('size' => 40)) ?></td>
                    <td><?php echo CHtml::filterLikeTextField('r.ejamaat_no', $filter, array('size' => 40)) ?></td>
                    <td><?php echo CHtml::filterLikeTextField('r.momin', $filter, array('size' => 40)) ?></td>
                    <td><?php echo CHtml::filterDropDownList('r.created_by_id', $filter, $arrUsers) ?></td>
                    <td align="right">
                        <a onclick="filter();" class="filter_ico"></a>
                        <a onclick="reset();" class="reset_ico"></a>
                    </td>
                </tr>

                <?php if ($envelopes) { ?>
                    <?php foreach ($envelopes as $envelope) { ?>
                        <tr>
                            <td class="left"><?php echo $envelope['date']; ?></td>
                            <td class="left"><?php echo $envelope['barcode_no']; ?></td>
                            <td class="left"><?php echo $envelope['receipt_no']; ?></td>
                            <td class="left"><?php echo $envelope['mode_of_payment']; ?></td>
                            <td class="left"><?php echo $envelope['receipt_type']; ?></td>
                            <td class="left"><?php echo $envelope['sfno']; ?></td>
                            <td class="left"><?php echo $envelope['ejamaat_no']; ?></td>
                            <td class="left"><?php echo $envelope['momin']; ?></td>
                            <td class="left"><?php echo $envelope['created_by']; ?></td>
                            <td class="right">
                                <?php if($envelope['receipt_status_id'] !=2): ?>
                                <?php if(!$is_module_aamil && !$is_module_aamil_view): ?>
                                [<a onclick="window.open('<?php echo makeUrl('vajebaat/receipt/print_receipt') . '&receipt_id=' . $envelope['id']; ?>', 'ReceiptPrint', 'width=600,height=600,scrollbars=no,left=400')"><?php echo $text_print; ?></a>]&nbsp;
                                [<a onclick="window.open('<?php echo makeUrl('vajebaat/receipt/print_receipt') . '&receipt_type=ALL&receipt_id=' . $envelope['id']; ?>', 'ReceiptPrint', 'width=600,height=600,scrollbars=no,left=400')">PRINT ALL</a>]&nbsp;
                                <?php foreach ($envelope['action'] as $action) { ?>
                                    [<a onclick = 'return confirm("Are you sure you want to cancel this Receipt?")' href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>]&nbsp;
                                <?php } ?>
                                <?php endif; ?>
                                <?php else: ?>
                                Cancelled
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td class="center" colspan="9"><?php echo $text_no_results; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php if($user_group == 1): ?>
<div class="pagination"><?php echo $pagination; ?></div>
<?php endif; ?>
<script type="text/javascript">
    var link = '<?php echo makeUrl("vajebaat/receipt"); ?>';
    $(document).ready(function(){
        $('#barcode_no').focus();
    });
    
    $('#barcode_no').keypress(function(e) {
        var val = $(this).val();
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            if(val) {
                fnSubmit();
            } else {
                $('#sf_no').focus();
            }
        }
    })
    
    $('#sf_no').keypress(function(e) {
        var val = $(this).val();
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            if(val) {
                fnSubmit();
            } else {
                $('#ejamaat_no').focus();
            }
        4}
    })
    
    $('#ejamaat_no').keypress(function(e) {
        var val = $(this).val();
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            if(val) {
                fnSubmit();
            } else {
                $('#barcode_no').focus();
            }
        }
    })
    
    function fnSubmit() {
        var barcode = $('#barcode_no').val();
        var sf_no = $('#sf_no').val();
        var ejamaat_no = $('#ejamaat_no').val();
        
        //alert(barcode + '|' + sf_no + '|' + ejamaat_no);
        if(barcode != '') {
//            window.open('<?php echo makeUrl('vajebaat/receipt/print_receipt') . '&barcode_no='; ?>' + val, '', 'width=640,height=300,scrollbars=yes');
            location = '<?php echo makeUrl('vajebaat/receipt/view_receipt') . '&barcode_no='; ?>' + barcode ;
            $('#barcode_no').val('');
        } else if(sf_no != '') {
            location = '<?php echo makeUrl('vajebaat/receipt/view_receipt') . '&sf_no='; ?>' + sf_no ;
            $('#sf_no').val('');
        } else if(ejamaat_no != '') {
            location = '<?php echo makeUrl('vajebaat/receipt/view_receipt') . '&ejamaat_no='; ?>' + ejamaat_no ;
            $('#ejamaat_no').val('');
        }
    }
    
    $(document).ready(function() {
        $('#barcode_no').focus();
        <?php /*if($receipts): ?>
            <?php foreach($receipts as $receipt):?>
                window.open('<?php echo makeUrl('vajebaat/receipt_print') . '&no-layout=1&rid=' . $receipt; ?>', '', 'width=640,height=300,scrollbars=no')
            <?php endforeach; ?>
        <?php endif;*/ ?>
    })
</script>