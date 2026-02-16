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
                        <li><a tabindex="6" class="btn btn-outline btn-primary btn-sm" href="javascript:void(0);" onclick="$('#form').submit();"><i class="fa fa-floppy-o"></i><?php echo $button_save; ?></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                                <input type="hidden" id="momin_id" name="momin_id" value="<?php echo $momin_id; ?>" class="form-control" />
                                <div class="form-group">
                                    <label><?php echo $entry_sf_no; ?></label>
                                    <input type="text" id="sf_no" name="sf_no" value="<?php echo $sf_no; ?>" class="form-control" tabindex="1" />
                                </div>
                                <div class="form-group">
                                    <label><?php echo $entry_ejamaat_no; ?></label>
                                    <input type="text" id="ejamaat_no" name="ejamaat_no" value="<?php echo $ejamaat_no; ?>" class="form-control" tabindex="2" />
                                </div>
                                <div class="form-group">
                                    <label><?php echo $entry_mohallah; ?></label>
                                    <input type="text" id="mohallah" name="mohallah" value="<?php echo $mohallah; ?>" class="form-control" disabled="true" tabindex="3"/>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $entry_momin; ?></label>
                                    <input type="text" id="momin" name="momin" value="<?php echo $momin; ?>" class="form-control" disabled="true" tabindex="4" />
                                </div>
                                <div class="form-group">
                                    <label><?php echo $entry_amount; ?></label>
                                    <input type="text" id="amount" name="amount" value="<?php echo $amount; ?>" class="form-control" tabindex="5" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="view/js/plugins/validate/jquery.validate.min.js"></script>
<script>
    jQuery('#form').validate(<?php echo $strValidation; ?>);

    $(document).ready(function() {
        $('#sf_no').focus();
    });

    $('#sf_no').on('keypress',function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            var val = $('#sf_no').val();
            if(!val) {
//                $('#amount').focus();
//            } else {
                $('#ejamaat_no').focus();
            }
        }
    });

    $('#ejamaat_no').on('keypress',function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            var val = $('#ejamaat_no').val();
            if(!val) {
//                $('#amount').focus();
//            } else {
                $('#sf_no').focus();
            }
        }
    });

    $('#sf_no').on('change',function() {
        var sf_no = $('#sf_no').val();
        if(sf_no != '' && sf_no != '9999') {
            $.ajax({
                url: '<?php echo HTTP_SERVER; ?>index.php?route=setup/takmeen/getMominBySF&token=<?php echo $token; ?>',
                dataType: 'json',
                type: 'post',
                data: 'sf_no=' + sf_no,
                beforeSend: function() {
                    $('#sf_no').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
                },
                complete: function() {
                    $('.wait').remove();
                },
                success: function(json) {
                    if(json.success) {
                        $('#momin_id').val(json.momin_id);
                        $('#ejamaat_no').val(json.ejamaat_no);
                        $('#mohallah').val(json.mohallah);
                        $('#momin').val(json.momin);
                        $('#amount').val(json.amount);
                        $('#amount').focus();
                    } else {
                        alert(json.error);
                        $('#momin').focus(function(){
                            this.select();
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    });

    $('#ejamaat_no').on('change',function() {
        var ejamaat_no = $('#ejamaat_no').val();
        if(ejamaat_no) {
            $.ajax({
                url: '<?php echo HTTP_SERVER; ?>index.php?route=setup/takmeen/getMominByEjamaat&token=<?php echo $token; ?>',
                dataType: 'json',
                type: 'post',
                data: 'ejamaat_no=' + this.value,
                beforeSend: function() {
                    $(this).after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
                },
                complete: function() {
                    $('.wait').remove();
                },
                success: function(json) {
                    if(json.success) {
                        $('#ejamaat_no').val(json.ejamaat_no);
                        $('#mohallah').val(json.mohallah);
                        $('#momin').val(json.momin);
                    } else {
                        alert(json.error);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    });
</script>
<?php echo $footer; ?>