<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">Dashboard</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Mohallah:
                    <select name="mohallah_id" id="mohallah_id" onchange="getChartData();">
                        <option value="">&nbsp;</option>
                        <?php foreach($mohallahs as $mohallah): ?>
                        <option value="<?php echo $mohallah['id']; ?>"><?php echo $mohallah['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">Form Printing Status</div>
                                <div class="panel-body">
                                    <div class="flot-chart">
                                        <div class="flot-chart-content" id="flot-pie-chart-form"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">Takmeen Status</div>
                                <div class="panel-body">
                                    <div class="flot-chart">
                                        <div class="flot-chart-content" id="flot-pie-chart-takmeen"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
</div>
<!-- Page-Level Plugin Scripts - Flot -->
<!--[if lte IE 8]>
<script src="view/js/excanvas.min.js"></script>
<![endif]-->
<script src="view/js/plugins/flot/jquery.flot.js"></script>
<script src="view/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="view/js/plugins/flot/jquery.flot.resize.js"></script>
<script src="view/js/plugins/flot/jquery.flot.pie.js"></script>
<script type="text/javascript">
    function getChartData() {
        var mohallah_id = $('#mohallah_id').val();
        $.ajax({
            url: '<?php echo HTTP_SERVER; ?>index.php?route=common/home/getChartData&token=<?php echo $token; ?>',
            type: 'POST',
            dataType: "json",
            data: 'mohallah_id=' + mohallah_id,
            beforeSend: function(){
                $('#mohallah_id').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('.wait').remove();
            },
            success: function(output){
                var dataPrinted = JSON.parse(output.dataPrinted);
                var dataTakmeen = JSON.parse(output.dataTakmeen);
                if(output.success) {
                    var plotObjPrinted = $.plot($("#flot-pie-chart-form"), dataPrinted, {
                        series: {
                            pie: {
                                show: true,
                                label: {
                                    show:true
                                }
                            }
                        },
                        grid: {
                            hoverable: true
                        },
                        tooltip: true,
                        tooltipOpts: {
                            content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                            shifts: {
                                x: 20,
                                y: 0
                            },
                            defaultTheme: false
                        }
                    });
                    var plotObjTakmeen = $.plot($("#flot-pie-chart-takmeen"), dataTakmeen, {
                        series: {
                            pie: {
                                show: true,
                                label: {
                                    show:true
                                }
                            }
                        },
                        grid: {
                            hoverable: true
                        },
                        tooltip: true,
                        tooltipOpts: {
                            content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                            shifts: {
                                x: 20,
                                y: 0
                            },
                            defaultTheme: false
                        }
                    });
                } else {
                    alert('Error Occured')
                }
            }
        });
    }
    $('#mohallah_id').trigger('change');
</script>
<?php echo $footer; ?>