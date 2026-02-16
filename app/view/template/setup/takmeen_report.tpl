<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="page-wrapper">
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading heading">
                    <?php echo $heading_title; ?>
                    <ul style="float: right;" class="list-nostyle list-inline">
                        <li><a tabindex="7" class="btn btn-outline btn-default btn-sm" href="<?php echo $cancel; ?>"><i class="fa fa-undo"></i><?php echo $button_cancel; ?></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label><?php echo $entry_from_date; ?></label>
                                <input type="text" id="from_date" name="from_date" value="<?php echo $from_date; ?>" class="form-control dtpDate" tabindex="1" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label><?php echo $entry_to_date; ?></label>
                                <input type="text" id="to_date" name="to_date" value="<?php echo $to_date; ?>" class="form-control dtpDate" tabindex="1" />
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><?php echo $entry_mohallah; ?></label>
                                <select id="mohallah_id" name="mohallah_id" class="form-control">
                                    <option value="">&nbsp;</option>
                                    <?php foreach($mohallahs as $mohallah_id => $mohallah_name): ?>
                                    <option value="<?php echo $mohallah_id; ?>"><?php echo $mohallah_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label><?php echo $entry_sort_order; ?></label>
                                <select id="sort_order" name="sort_order" class="form-control">
                                    <option value="sf_no">SF#</option>
                                    <option value="ejamaat_no">ITS#</option>
                                    <option value="full_name">Name</option>
                                    <option value="created_at">Date</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="form-control btn btn-primary" onclick="printReport();">Print</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="view/js/plugins/validate/jquery.validate.min.js"></script>
<script>

    function printReport() {
        var $URLPrintReport = '<?php echo $href_print_report; ?>';
        var $from_date = $('#from_date').val();
        var $to_date = $('#to_date').val();
        var $mohallah_id = $('#mohallah_id').val();
        var $sort_order = $('#sort_order').val();

        if($from_date == '') {
            alert ('From Date is required');
            return;
        }
        if($to_date == '') {
            alert ('From Date is required');
            return;
        }
        // if($mohallah_id == '') {
        //     alert ('Mohallah is required');
        //     return;
        // }

        var $URL = $URLPrintReport + '&from_date='+ $from_date + '&to_date='+ $to_date + '&mohallah_id='+$mohallah_id + '&sort_order='+$sort_order;
        window.open($URL,'_blank');
    }

</script>
<?php echo $footer; ?>