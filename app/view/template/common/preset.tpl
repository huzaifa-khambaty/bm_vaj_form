<?php echo $header; ?>
<div id="container">
    <div class="row">
        <!--        <div class="heading">
                    <h1><img src="view/image/lockscreen.png" alt="" /> <?php echo $text_preset; ?></h1>
                </div>-->
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-body">
                    <?php if ($success) { ?>
                    <div class="success"><?php echo $success; ?></div>
                    <?php } ?>
                    <?php if ($error_warning) { ?>
                    <div class="warning"><?php echo $error_warning; ?></div>
                    <?php } ?>
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                        <fieldset>
                            <div class="form-group">
                                <?php echo $entry_company; ?>
                                <select class="form-control" name="company_id" id="company_id">
                                    <?php foreach($companys as $company):?>
                                    <option value="<?php echo $company['company_id']; ?>" <?php echo ($company['company_id'] = $company_id ? 'selected="selected"' : ''); ?>><?php echo $company['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <?php echo $entry_company_branch; ?><br />
                                <select class="form-control" name="company_branch_id" id="company_branch_id">
                                		<?php foreach($company_branch_id as $companybranch):?>
                                    <option value="<?php echo $companybranch['company_branch_id']; ?>" <?php echo ($companybranch['company_branch_id'] = $company_branch_id ? 'selected="selected"' : ''); ?>><?php echo $companybranch['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <?php echo $entry_fiscal_year; ?>
                                <select class="form-control" name="fiscal_year_id" id="fiscal_year_id">
                                    <?php foreach($fiscal_years as $fiscal_year):?>
                                    <option value="<?php echo $fiscal_year['fiscal_year_id']; ?>" <?php echo ($fiscal_year['fiscal_year_id'] == $fiscal_year_id ? 'selected="selected"' : ''); ?>><?php echo $fiscal_year['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <a onclick="$('#form').submit();" class="btn btn-lg btn-login btn-block"><span><?php echo $button_submit; ?></span></a>
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
<script type="text/javascript"><!--
$('#company_id').bind('change', function() {
$.ajax({
url: '<?php echo HTTP_SERVER; ?>index.php?route=setup/company/getBranches&token=<?php echo $token; ?>',
dataType: 'json',
type: 'post',
data: 'company_id=' + this.value,
beforeSend: function() {
$('#company_branch_id').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
},
complete: function() {
$('.wait').remove();
},			
success: function(json) {
if(json.success) {
var html = '';
if (json['company_branches'] != '') {
for (i = 0; i < json['company_branches'].length; i++) {
html += '<option value="' + json['company_branches'][i]['company_branch_id'] + '"';

if (json['company_branches'][i]['company_branch_id'] == '<?php echo $company_branch_id; ?>') {
html += ' selected="selected"';
}

html += '>' + json['company_branches'][i]['name'] + '</option>';
}
}
}


$('#company_branch_id').html(html);
},
error: function(xhr, ajaxOptions, thrownError) {
alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
}
});
});

$('#company_id').trigger('change');
//--></script>
<?php echo $footer; ?>