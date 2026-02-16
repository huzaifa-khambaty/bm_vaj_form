<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="page-wrapper">
<!--  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>-->
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="row">
  <div class="box">
			<!--<div class="heading">
			  <h1><img src="view/image/backup.png" alt="" /> <?php echo $heading_title; ?></h1>
			</div>-->
	<div class="row">
                <div class="col-lg-12">
					<div class="panel panel-default">
					<div class="panel-heading">
						<?php echo $heading_title; ?>
					</div>		
      <form action="<?php echo $restore; ?>" method="post" enctype="multipart/form-data" id="restore">
        <table class="form">
          <tr>
            <td><?php echo $entry_restore; ?></td>
            <td><input type="file" name="import" /></td>
          </tr>
        </table>
      </form>
      <form action="<?php echo $backup; ?>" method="post" enctype="multipart/form-data" id="backup">
	  <div class="table-responsive">
			   			<div class="col-lg-6">
						<table class="form">
						  <tr>
							<td><?php echo $entry_backup; ?></td>
							<td><div class="scrollbox" style="margin-bottom: 5px;">
								<?php $class = 'odd'; ?>
								<?php foreach ($tables as $table) { ?>
								<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
								<div class="<?php echo $class; ?>">
								  <input type="checkbox" name="backup[]" value="<?php echo $table; ?>" checked="checked" />
								  <?php echo $table; ?></div>
								<?php } ?>
							  </div></td>
						  </tr>
						</table>
		<div class="buttons" style="margin-bottom:20px;"><a onclick="$('#restore').submit();" class="btn btn-default"><span><?php echo $button_restore; ?></span></a><a onclick="$('#backup').submit();" class="btn btn-default"><span><?php echo $button_backup; ?></span></a></div>
		   </div>
		</div>
      </form>
	     </div>
	    </div>
	  </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>