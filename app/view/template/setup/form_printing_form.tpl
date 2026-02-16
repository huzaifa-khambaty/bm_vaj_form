<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="page-wrapper">
    <?php if ($error_warning) { ?>
    <div class="row">   <!--START row-->
        <div class="col-lg-12"> <!--START col-lg-12-->
            <div class="alert alert-warning alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <?php echo $error_warning; ?>
            </div>
        </div>  <!--END col-lg-12-->  
    </div>  <!--END row-->
    <?php } ?>
    <?php if ($success) { ?>
    <div class="row">   <!--START row-->
        <div class="col-lg-12"> <!--START col-lg-12-->
            <div class="alert alert-success alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <?php echo $success; ?>
            </div>
        </div>  <!--END col-lg-12-->  
    </div>  <!--END row-->
    <?php } ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading heading">
                    <?php echo $heading_title; ?>
<!--                    <ul style="float: right;" class="list-nostyle list-inline">
                        <li><a class="btn btn-outline btn-default btn-sm" href="<?php echo $cancel; ?>"><i class="fa fa-undo"></i><?php echo $button_cancel; ?></a></li>
                        <li><a class="btn btn-outline btn-primary btn-sm" href="javascript:void(0);" onclick="$('#form').submit();"><i class="fa fa-floppy-o"></i><?php echo $button_save; ?></a></li>
                    </ul> -->
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo $entry_sf_no; ?></label>
                                <input type="text" id="sf_no" name="sf_no" value="" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo $entry_ejamaat_no; ?></label>
                                <input type="text" id="ejamaat_no" name="ejamaat_no" value="" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo $entry_mohallah; ?></label>
                                <select id="mohallah_id" name="mohallah_id" class="form-control">
                                    <option value="">&nbsp;</option>
                                    <?php foreach($mohallahs as $mohallah_id => $mohallah): ?>
                                    <option value="<?php echo $mohallah_id ?>"><?php echo $mohallah; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-8 col-md-4">
                            <ul style="float: right;" class="list-nostyle list-inline">
                                <li><a class="btn btn-outline btn-primary btn-sm" href="javascript:void(0);" onclick="getRows();"><i class="fa fa-search"></i><?php echo $button_view; ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="divFormData">
                        </div>
                    </div>
                </div>	
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function getRows() {
        var sf_no = $('#sf_no').val();
        var ejamaat_no = $('#ejamaat_no').val();
        var mohallah_id = $('#mohallah_id').val();
        if(sf_no == '' && ejamaat_no=='' && mohallah_id=='') {
            alert('Select atleast 1 Filter');
        } else {
            $.ajax({
                url: '<?php echo HTTP_SERVER; ?>index.php?route=setup/form_printing/getRows&token=<?php echo $token; ?>',
                dataType: 'json',
                type: 'POST',
                data: 'sf_no=' + sf_no + '&ejamaat_no=' + ejamaat_no + '&mohallah_id=' + mohallah_id,
                beforeSend: function() {
                    $('#tblFormData').remove();
                    $('#divFormData').append('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
                },
                complete: function() {
                    $('.wait').remove();
                },
                success: function(json) {
                    if(json.status) {
                        $('#divFormData').html(json.html);
                    } else {
                        console.log(json);
                        alert(json.error);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    }

    function printAll() {
        var eleID = '';
        if($('#tblFormData tbody input[type=checkbox]:checked').length > 10) {
            alert ('Cannot Print more than 10 records');
        } else {
            var arrID = [];
            $('#tblFormData tbody input[type=checkbox]:checked').each(function(i) {
                eleID = $(this).parent().parent().attr('row_id');
                //delayedTrigger( $('#'+eleID+' span'), 800*i );
                arrID[i] = eleID;
            });

            window.open('<?php echo $action_print_all; ?>&eid=' + arrID,'_blank');
        }

        function delayedTrigger(elem, delay)
        {
            setTimeout( function() { $(elem).trigger('click'); }, delay );
        }

    }

    function print2All() {
        var eleID = '';
        if($('#tblFormData tbody input[type=checkbox]:checked').length > 10) {
            alert ('Cannot Print more than 10 records');
        } else {
            var arrID = [];
            $('#tblFormData tbody input[type=checkbox]:checked').each(function(i) {
                eleID = $(this).parent().parent().attr('row_id');
                //delayedTrigger( $('#'+eleID+' span'), 800*i );
                arrID[i] = eleID;
            });

            window.open('<?php echo $action_print2_all; ?>&eid=' + arrID,'_blank');
        }

        function delayedTrigger(elem, delay)
        {
            setTimeout( function() { $(elem).trigger('click'); }, delay );
        }

    }
</script>
<?php echo $footer; ?>