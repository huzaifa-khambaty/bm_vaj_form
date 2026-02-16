    <div class="loginfooter">
        <?php echo $text_footer; ?>
    </div>
</div>
<script type="text/javascript" src="view/js/plugins/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
    $('.dtpDate').on('click', function() {
        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            showOn:'focus', 
            dateFormat: '<?php echo STD_DATE_PICKER; ?>'
        }).focus();
    });
    $('.dtpDateTime').on('click', function() {
        $(this).datetimepicker({
            changeMonth: true,
            changeYear: true,
            showOn:'focus',
            dateFormat: '<?php echo STD_DATE_PICKER; ?>',
            timeFormat: 'h:m'
        }).focus();
    });
    $('.dtpTime').on('click', function() {
        $(this).datepicker({
            showOn:'focus', 
            timeFormat: 'h:m'
        }).focus();
    });
//--></script>

    <script type="text/javascript">
        $(document).ready(function() {
            jQuery('.alert-dismissable').delay(5000).fadeOut('slow');

            $('.fInteger').on('focus',function() {
                $(this).format({precision: 0,autofix:true});
            });
            $('.fPInteger').on('focus',function() {
                $(this).format({precision: 0,allow_negative:false,autofix:true});
            });
            $('.fDecimal').on('focus',function() {
                $(this).format({precision: 2,autofix:true});
            });
            $('.fPDecimal').on('focus',function() {
                $(this).format({precision: 2,allow_negative:false,autofix:true});
            });
            $('.fFloat').on('focus',function() {
                $(this).format({precision: 6,autofix:true});
            });
            $('.fPFloat').on('focus',function() {
                $(this).format({precision: 6,allow_negative:false,autofix:true});
            });
            $('.fEmail').on('focus',function() {
                $(this).format({type:"email"},function(){
                    if($(this).val()!="") alert("Wrong Email format!");
                });
            });
            $('.fString').on('focus',function() {
                $(this).format({type:"alphabet",autofix:true});
            });
        });
    </script>

	<script type="text/javascript">
        function ConfirmDelete(url) {
            $( "#confirmDelete" ).dialog({
                dialogClass: "confirmDelete",
                title: 'Confirm Delete?',
                buttons: [
                    {
                        text: "Cancel",
                        click: function() {
                            $( this ).dialog( "close" );
                        }
                    },
                    {
                        text: "Delete",
                        click: function() {
                            location.href=url;
                        }
                    }
                ]
            });

            var buttons = $('.confirmDelete .ui-dialog-buttonset').children();
            $(buttons[0]).addClass('btn');
            $(buttons[1]).addClass('btn').addClass('btn-primary');
        }
    </script>

<script type="text/javascript"><!--
    $('#tabs a').tabs(); 
    $('#languages a').tabs(); 
    $('#vtab-option a').tabs();
$(document).ready(function() {
    $('.QSearch').on('click',function() {
        ref_id = $(this).attr('ref_id');
        search = $(this).attr('search');
        callback = $(this).attr('callback');
        $('#_quickSearch_').remove();
        $('#content').prepend('<div id="_quickSearch_" style="padding: 3px 0px 0px 0px;"></div>');
        $.ajax({
            url: '<?php echo HTTP_SERVER; ?>index.php?route=common/quick_search/'+search+'&token=<?php echo $token; ?>&ref_id=' + ref_id + '&callback=' + callback,
            dataType: 'json',
            beforeSend: function() {
                $(this).after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('.wait').remove();
            },			
            success: function(json) {
                if(json.success) {
                    $('#_quickSearch_').html(json.html);
                    $('#_quickSearch_').dialog({
                        title: 'Quick Search',
                        close: function (event, ui) {
                            $('#_quickSearch_').remove();
                        },	
                        bgiframe: false,
                        width: 800,
                        height: 520,
                        resizable: false,
                        modal: true
                    });
                        
                    var oTable = $('#_quickSearch_ table').dataTable();
                    
                    $("thead th").each( function ( i ) {
                        this.innerHTML = fnCreateSelect( oTable.fnGetColumnData(i+1) );
                        $('select', this).change( function () {
                            oTable.fnFilter( $(this).val(), i+1 );
                        });
                    });
                        
                } else {
                    alert(json.error);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
            
    });
})
    
function _quickSearch(ref_id, ref_val, callback, json) {
    $('#' + ref_id).val(ref_val);
    $('#_quickSearch_').remove();
    if(callback) {
        if (typeof(window[callback]) == "function") {
            // execute the callback, passing parameters as necessary
            if(json) {
                window[callback](urldecode(json));
            } else {
                window[callback](ref_id);
            }
        } else {
            alert('Function: ' + callback + ' is missing');
        }
    }
}

function urldecode (str) {
    return decodeURIComponent((str + '').replace(/%(?![\da-f]{2})/gi, function () {
        // PHP tolerates poorly formed escape sequences
        return '%25';
    }).replace(/\+/g, '%20'));
}

//--></script> 
    <script type="text/javascript"><!--
        function image_upload(field, thumb) {
            $('#dialog').remove();
            $('#page-wrapper').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="<?php echo HTTP_SERVER; ?>index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
            $('#dialog').dialog({
                title: '<?php echo $text_image_manager; ?>',
                close: function (event, ui) {
                    if ($('#' + field).attr('value')) {
                        $.ajax({
                            url: '<?php echo HTTP_SERVER; ?>index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
                            dataType: 'text',
                            success: function(text) {
                                $('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
                            }
                        });
                    }
                },	
                bgiframe: false,
                width: 800,
                height: 400,
                resizable: false,
                modal: false
            });
        };  
    //--></script>
        <div id="confirmDelete" style="display: none;">
            <p>Are you sure to delete this record permanently?</p>
        </div>
    </body>
</html>