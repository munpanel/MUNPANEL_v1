$(document).ready(function() {
   $('#delegate-table').DataTable({
        //processing: true,
        //serverSide: true,
        ajax: 'ajax/roleAllocDelegates',
        columns: [
            {data: 'name', name: 'name', orderable: true},
            {data: 'school', name: 'school', orderable: false},
            {data: 'nation', name: 'nation', orderable: true},
            {data: 'command', name: 'command', orderable: false}
            ],
            fnInitComplete: function(oSettings, json) {
                $(document).on('click', '.addButton', function(){
                    $.post("dais/addSeat/" + $(this).attr('del-id'), $('#seatform').serialize(), function(receivedData){
                        //if (receivedData == "success")
                        location.reload();
                        //useTheResponseData(receivedData);
                        });
                });

            $(document).on('hidden.bs.modal', '#ajaxModal', function() {
                $('#delegate-table').dataTable().fnReloadAjax(undefined, undefined, true);
            });
            //$('#delegate-table_paginate').hide();
            $('#delegate-table_length').hide();
            $('.dataTables_filter').hide();
            $('#delegate-table_info').appendTo($('#nation-pageinfo'));
            $('#delegate-table').removeClass('no-footer');
            },
        "fnDrawCallback": function( oSettings ) {
            $('#nation-pagnination').empty();
            $('.paginate_button').each(function (i) {
                var li = $("<li></li>");
                li.appendTo($('#nation-pagnination'));
                $(this).attr({href: "#"});
                $(this).appendTo(li);
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
        "order": [[1, "asc"]],
    });
    var table=$('#delegate-table').DataTable();
    $("#searchButton").click(function() {
       table.search($('#searchbox').val()).draw();
    });    
    $("#nation-length-select").change(function() {
        table.page.len($(this).val()).draw();
    });
});
