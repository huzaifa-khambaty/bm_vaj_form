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
                    <ul class="list-nostyle list-inline pull-right">
                        <li><a class="btn btn-outline btn-primary btn-sm" href="<?php echo $action_insert; ?>"><i class="fa fa-plus"></i><?php echo $button_insert; ?></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <form action="#" method="post" enctype="multipart/form-data" id="form">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="dataTable" class="table table-striped table-bordered table-hover" align="center">
                                    <thead>
                                        <tr>
                                            <td class="center"><?php echo $column_action; ?></td>
                                            <td class="center"><?php if ($order == 'name') { ?>
                                                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($sort); ?>"><?php echo $column_name; ?></a>
                                                <?php } else { ?>
                                                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                            <?php } ?></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>		
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
    var adelete = '<?php echo html_entity_decode($action_delete); ?>';
    var afilter = '<?php echo html_entity_decode($action_filter); ?>';
    
    $('#btnDelete').click(function() {
        $('#form').attr('action', adelete);
        $('#form').submit();
    });
    
    $('#btnFilter').click(function() {
        $('#form').attr('action', afilter);
        $('#form').submit();
    });
    
    $(document).ready(function() {
        $('.dpDate').datepicker({dateFormat: 'yy-mm-dd'});
    });
//--></script>
<script src="view/js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="view/js/plugins/dataTables/dataTables.bootstrap.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        oTable = jQuery('#dataTable').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            "bFilter": true,
            "bAutoWidth": false,
            "sPaginationType": "full_numbers",
            "sAjaxSource": "<?php echo $action_ajax; ?>"
            ,"aoColumnDefs" : [ {
                'bSortable' : false,
                'aTargets' : [ 0 ]
            }, {
                'bSearchable' : false,
                'aTargets' : [ 0 ]
            } ]
        });
        $('.dataTables_wrapper div.dataTables_length').parent().addClass('col-xs-6');
        $('.dataTables_wrapper div.dataTables_filter').parent().addClass('col-xs-6');
    });
</script>
<?php echo $footer; ?> 