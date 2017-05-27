$(document).ready(function() {
   $('#delegate-table').DataTable({
        //processing: true,
        //serverSide: true,
        ajax: 'ajax/roleAllocDelegates',
        columns: [
            {data: 'uid', name: 'uid', orderable: true},
            {data: 'name', name: 'name', orderable: false},
            {data: 'committee', committee: 'committee', orderable: true},
            {data: 'nation', name: 'nation', orderable: false},
            {data: 'command', name: 'command', orderable: false}
            ],
            fnInitComplete: function(oSettings, json) {
                $(document).on('click', '.addButton', function(){
                    var $del_id = $(this).attr('del-id');
                    $.post("dais/addSeat/" + $del_id, $('#seatform').serialize(), function(receivedData){
                        if (receivedData == "prompt") {
                            $('#ajaxModal').modal('hide');
                            $('#ajaxModal').remove();
                            var $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
                            $('body').append($modal);
                            $modal.modal();
                            $modal.load('dais/addSeat/' + $del_id + '/modal');
                        } else {
                            //if (receivedData != "success")
                            //    alert(receivedData);
                            //location.reload();
                            $.snackbar({content: data});
                            $('#delegate-table').dataTable().fnReloadAjax(undefined, undefined, true);
                            $('#nation-table').dataTable().fnReloadAjax(undefined, undefined, true);
                        }
                    });
                });

            $(document).on('hidden.bs.modal', '#ajaxModal', function() {
                $('#delegate-table').dataTable().fnReloadAjax(undefined, undefined, true);
            });
            //$('#delegate-table_paginate').hide();
            $('#delegate-table_length').hide();
            $('.dataTables_filter').hide();
            $('#delegate-table_info').appendTo($('#delegate-pageinfo'));
            $('#delegate-table').removeClass('no-footer');
            },
        "fnDrawCallback": function( oSettings ) {
            $.snackbar({content: 'Delegates refreshed'});
            $('#delegate-pagnination').empty();
            $('.paginate_button').each(function (i) {
                if ($(this).attr('aria-controls') == 'delegate-table')
                {
                    var li = $("<li></li>");
                    li.appendTo($('#delegate-pagnination'));
                    $(this).attr({href: "#"});
                    $(this).appendTo(li);
                }
             });
            $('.paginate_button.previous').html("<i class='fa fa-chevron-left'></i>");
            $('.paginate_button.next').html("<i class='fa fa-chevron-right'></i>");
        },
        "language": {
            "zeroRecords": "没有找到记录",
            "info": "第 _PAGE_ 页 ( 总共 _PAGES_ 页 )",
            "infoEmpty": "无记录",
            "infoFiltered": "(从 _MAX_ 条记录过滤)"
        },
        "bSort": false,
    });
    var deltable=$('#delegate-table').DataTable();
    $("#delegate-searchButton").click(function() {
       deltable.search($('#delegate-searchBox').val()).draw();
    });
    $("#delegate-length-select").change(function() {
        deltable.page.len($(this).val()).draw();
    });
});
