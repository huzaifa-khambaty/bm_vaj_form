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
                        <li><a class="btn btn-outline btn-default btn-sm" href="<?php echo $cancel; ?>"><i class="fa fa-undo"></i><?php echo $button_cancel; ?></a></li>
                        <li><a class="btn btn-outline btn-primary btn-sm" href="javascript:void(0);" onclick="$('#form').submit();"><i class="fa fa-floppy-o"></i><?php echo $button_save; ?></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $entry_username; ?></label>
                                        <input type="text" name="username" value="<?php echo $username; ?>" class="form-control" />
                                        <?php if (isset($error['username'])) { ?>
                                        <span class="error"><?php echo $error['username']; ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo $entry_firstname; ?></label>
                                        <input type="text" name="firstname" value="<?php echo $firstname; ?>" class="form-control" />
                                        <?php if (isset($error['firstname'])) { ?>
                                        <span class="error"><?php echo $error['firstname']; ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo $entry_lastname; ?></label>
                                        <input type="text" name="lastname" value="<?php echo $lastname; ?>" class="form-control"/>
                                        <?php if (isset($error['lastname'])) { ?>
                                        <span class="error"><?php echo $error['lastname']; ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo $entry_email; ?></label>
                                        <input type="text" name="email" value="<?php echo $email; ?>" autocomplete="off" class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo $entry_password; ?></label>
                                        <input type="password" id="Password" name="password" value="" autocomplete="off" class="form-control" />
                                        <?php if (isset($error['password'])) { ?>
                                        <span class="error"><?php echo $error['password']; ?></span>
                                        <?php  } ?>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo $entry_confirm; ?></label>
                                        <input type="password" name="confirm" value="" autocomplete="off" class="form-control"/>
                                        <?php if (isset($error['confirm'])) { ?>
                                        <span class="error"><?php echo $error['confirm']; ?></span>
                                        <?php  } ?>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo $entry_user_permission; ?></label>
                                        <select class="form-control" name="user_permission_id">
                                            <?php foreach ($user_permissions as $user_permission) { ?>
                                            <?php if ($user_permission['user_permission_id'] == $user_permission_id) { ?>
                                            <option value="<?php echo $user_permission['user_permission_id']; ?>" selected="selected"><?php echo $user_permission['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $user_permission['user_permission_id']; ?>"><?php echo $user_permission['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Mohallahs</label>
                                        <?php foreach($mohallahs as $mohallah): ?>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="user_mohallahs[]" value="<?php echo $mohallah['id']; ?>" <?php echo (in_array($mohallah['id'],$user_mohallahs)?'checked="true"':''); ?>><?php echo $mohallah['name']; ?>
                                            </label>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
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
</script>
<?php echo $footer; ?>