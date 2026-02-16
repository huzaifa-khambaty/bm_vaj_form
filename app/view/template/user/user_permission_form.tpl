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
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading heading">
                    <?php echo $heading_title; ?>
                    <ul style="float: right;" class="list-nostyle list-inline">
                        <li><a class="btn btn-outline btn-default btn-sm" href="<?php echo $cancel; ?>"><i class="fa fa-undo"></i><?php echo $button_cancel; ?></a></li>
                        <li><a class="btn btn-outline btn-primary btn-sm" href="javascript:void(0);" onclick="$('#form').submit();"><i class="fa fa-floppy-o"></i><?php echo $button_save; ?></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                                <div class="form-group">
                                    <label><?php echo $entry_name; ?></label>
                                    <input type="text" name="name" value="<?php echo $name; ?>" class="form-control" />
                                </div>
                                <div>
                                    <label><?php echo $entry_access; ?></label>
                                </div>
                                <?php $row=0; ?>
                                <div>
                                    <label style="width: 25%;"><input type="checkbox" id="chk_all" name="chk_all" value="1" />&nbsp;<?php echo $column_all; ?></label>
                                    <label style="width: 12%;"><input type="checkbox" id="chk_view" name="chk_view" value="1" />&nbsp;<?php echo $column_view; ?></label>
                                    <label style="width: 12%;"><input type="checkbox" id="chk_insert" name="chk_insert" value="1" />&nbsp;<?php echo $column_insert; ?></label>
                                    <label style="width: 12%;"><input type="checkbox" id="chk_update" name="chk_update" value="1" />&nbsp;<?php echo $column_update; ?></label>
                                    <label style="width: 12%;"><input type="checkbox" id="chk_delete" name="chk_delete" value="1" />&nbsp;<?php echo $column_delete; ?></label>
                                    <label style="width: 12%;"><input type="checkbox" id="chk_print" name="chk_print" value="1" />&nbsp;<?php echo $column_print; ?></label>
                                    <label style="width: 12%;"><input type="checkbox" id="chk_reprint" name="chk_reprint" value="1" />&nbsp;<?php echo $column_reprint; ?></label>
                                </div>
                                <?php $row++; ?>
                                <div>
                                    <label style="width: 25%;"><input id="<?php echo $row; ?>" onclick="fn_chk_frm('<?php echo $row; ?>');" type="checkbox" name="chk_form" value="user/user_permission" />&nbsp;<?php echo $label_access_control; ?></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="view" type="checkbox" name="permission[user/user_permission][view]" value="1" <?php echo ($permissions['user/user_permission']['view']==1?'checked="checked"':''); ?> /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="insert" type="checkbox" name="permission[user/user_permission][insert]" value="1" <?php echo ($permissions['user/user_permission']['insert']==1?'checked="checked"':''); ?> /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="update" type="checkbox" name="permission[user/user_permission][update]" value="1" <?php echo ($permissions['user/user_permission']['update']==1?'checked="checked"':''); ?> /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="delete" type="checkbox" name="permission[user/user_permission][delete]" value="1" <?php echo ($permissions['user/user_permission']['delete']==1?'checked="checked"':''); ?> /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="print" type="checkbox" name="permission[user/user_permission][print]" value="1" <?php echo ($permissions['user/user_permission']['print']==1?'checked="checked"':''); ?> style="display:none;" /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="reprint" type="checkbox" name="permission[user/user_permission][reprint]" value="1" <?php echo ($permissions['user/user_permission']['reprint']==1?'checked="checked"':''); ?> style="display:none;" /></label>
                                </div>
                                <?php $row++; ?>
                                <div>
                                    <label style="width: 25%;"><input id="<?php echo $row; ?>" onclick="fn_chk_frm('<?php echo $row; ?>');" type="checkbox" name="chk_form" value="user/user" />&nbsp;<?php echo $label_users; ?></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="view" type="checkbox" name="permission[user/user][view]" value="1" <?php echo ($permissions['user/user']['view']==1?'checked="checked"':''); ?> /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="insert" type="checkbox" name="permission[user/user][insert]" value="1" <?php echo ($permissions['user/user']['insert']==1?'checked="checked"':''); ?> /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="update" type="checkbox" name="permission[user/user][update]" value="1" <?php echo ($permissions['user/user']['update']==1?'checked="checked"':''); ?> /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="delete" type="checkbox" name="permission[user/user][delete]" value="1" <?php echo ($permissions['user/user']['delete']==1?'checked="checked"':''); ?> /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="print" type="checkbox" name="permission[user/user][print]" value="1" <?php echo ($permissions['user/user']['print']==1?'checked="checked"':''); ?> style="display:none;" /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="reprint" type="checkbox" name="permission[user/user][reprint]" value="1" <?php echo ($permissions['user/user']['reprint']==1?'checked="checked"':''); ?> style="display:none;" /></label>
                                </div>
                                <?php $row++; ?>
                                <div>
                                    <label style="width: 25%;"><input id="<?php echo $row; ?>" onclick="fn_chk_frm('<?php echo $row; ?>');" type="checkbox" name="chk_form" value="setup/form_printing" />&nbsp;<?php echo $label_form_printing; ?></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="view" type="checkbox" name="permission[setup/form_printing][view]" value="1" <?php echo ($permissions['setup/form_printing']['view']==1?'checked="checked"':''); ?> /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="insert" type="checkbox" name="permission[setup/form_printing][insert]" value="1" <?php echo ($permissions['setup/form_printing']['insert']==1?'checked="checked"':''); ?> style="display:none;" /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="update" type="checkbox" name="permission[setup/form_printing][update]" value="1" <?php echo ($permissions['setup/form_printing']['update']==1?'checked="checked"':''); ?> style="display:none;" /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="delete" type="checkbox" name="permission[setup/form_printing][delete]" value="1" <?php echo ($permissions['setup/form_printing']['delete']==1?'checked="checked"':''); ?> style="display:none;" /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="print" type="checkbox" name="permission[setup/form_printing][print]" value="1" <?php echo ($permissions['setup/form_printing']['print']==1?'checked="checked"':''); ?> /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="reprint" type="checkbox" name="permission[setup/form_printing][reprint]" value="1" <?php echo ($permissions['setup/form_printing']['reprint']==1?'checked="checked"':''); ?> /></label>
                                </div>
                                <?php $row++; ?>
                                <div>
                                    <label style="width: 25%;"><input id="<?php echo $row; ?>" onclick="fn_chk_frm('<?php echo $row; ?>');" type="checkbox" name="chk_form" value="setup/takmeen" />&nbsp;<?php echo $label_takmeen; ?></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="view" type="checkbox" name="permission[setup/takmeen][view]" value="1" <?php echo ($permissions['setup/takmeen']['view']==1?'checked="checked"':''); ?> /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="insert" type="checkbox" name="permission[setup/takmeen][insert]" value="1" <?php echo ($permissions['setup/takmeen']['insert']==1?'checked="checked"':''); ?> /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="update" type="checkbox" name="permission[setup/takmeen][update]" value="1" <?php echo ($permissions['setup/takmeen']['update']==1?'checked="checked"':''); ?> /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="delete" type="checkbox" name="permission[setup/takmeen][delete]" value="1" <?php echo ($permissions['setup/takmeen']['delete']==1?'checked="checked"':''); ?> /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="print" type="checkbox" name="permission[setup/takmeen][print]" value="1" <?php echo ($permissions['setup/takmeen']['print']==1?'checked="checked"':''); ?> /></label>
                                    <label style="width: 12%;"><input attr_name="<?php echo $row; ?>" attr_permission="reprint" type="checkbox" name="permission[setup/takmeen][reprint]" value="1" <?php echo ($permissions['setup/takmeen']['reprint']==1?'checked="checked"':''); ?> /></label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>	
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
    $('#chk_all').change(function () {
        if ($(this).is(':checked')) {
            $(':checkbox').prop('checked', true);
        } else {
            $(':checkbox').prop('checked', false);
        }
    });
    $('#chk_view').change(function() {
        if ($(this).is(':checked')) {
            $(':checkbox[attr_permission=view]').prop('checked', true);
        } else {
            $(':checkbox[attr_permission=view]').prop('checked', false);
        }
    });
    $('#chk_insert').click(function() {
        if ($(this).is(':checked')) {
            $(':checkbox[attr_permission=insert]').prop('checked', true);
        } else {
            $(':checkbox[attr_permission=insert]').prop('checked', false);
        }
    });
    $('#chk_update').click(function() {
        if ($(this).is(':checked')) {
            $(':checkbox[attr_permission=update]').prop('checked', true);
        } else {
            $(':checkbox[attr_permission=update]').prop('checked', false);
        }
    });
    $('#chk_delete').click(function() {
        if ($(this).is(':checked')) {
            $(':checkbox[attr_permission=delete]').prop('checked', true);
        } else {
            jQuery(':checkbox[attr_permission=delete]').prop('checked', false);
        }
    });

    $('#chk_print').click(function() {
        if ($(this).is(':checked')) {
            $(':checkbox[attr_permission=print]').prop('checked', true);
        } else {
            jQuery(':checkbox[attr_permission=print]').prop('checked', false);
        }
    });

    $('#chk_reprint').click(function() {
        if ($(this).is(':checked')) {
            $(':checkbox[attr_permission=reprint]').prop('checked', true);
        } else {
            jQuery(':checkbox[attr_permission=reprint]').prop('checked', false);
        }
    });

    function fn_chk_frm(id) {
        if($('#'+id).is(':checked')) {
            $(':checkbox[attr_name=' + id + ']').prop('checked', true);
        } else {
            $(':checkbox[attr_name=' + id + ']').prop('checked', false);
        }
    }
//--></script> 

<?php echo $footer; ?> 