<?php echo $header; ?>
<div id="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-body">
                    <?php if ($error_warning) { ?>
                    <div class="row">
                        <!--START row-->
                        <div class="col-lg-12">
                            <!--START col-lg-12-->
                            <div class="alert alert-warning alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <?php echo $error_warning; ?> </div>
                        </div>
                        <!--END col-lg-12-->
                    </div>
                    <!--END row-->
                    <?php } ?>
                    <?php if ($success) { ?>
                    <div class="row">
                        <!--START row-->
                        <div class="col-lg-12">
                            <!--START col-lg-12-->
                            <div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <?php echo $success; ?>
                            </div>
                        </div>
                        <!--END col-lg-12-->
                    </div>
                    <!--END row-->
                    <?php } ?>
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="login">
                        <fieldset>
                            <div class="form-group">
                                <?php echo $entry_username; ?>
                                <input class="form-control" type="text" name="username" value="<?php echo $username; ?>" autofocus="true" />
                            </div>
                            <div class="form-group">
                                <?php echo $entry_password; ?>
                                <input class="form-control" type="password" name="password" value="<?php echo $password; ?>" />
                            </div>
                            <a href="javascript:void(0);" onclick="$('#login').submit();" class="btn btn-lg btn-login btn-block">Sign In</a>
                        </fieldset>
                        <?php if ($redirect) { ?>
                        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
    $('#form input').keydown(function(e) {
        if (e.keyCode == 13) {
            $('#form').submit();
        }
    });
    //--></script>
<?php echo $footer; ?>