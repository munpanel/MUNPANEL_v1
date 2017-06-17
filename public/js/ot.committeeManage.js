$(document).ready(function() {
   $('#committee-table').DataTable({
        processing: true,
        //serverSide: true,
        ajax: 'ajax/committees',
        columns: [
            {data: 'details', name: 'details', orderable: false},
            {data: 'id', name: 'id', orderable: true},
            {data: 'bt', name: 'bt', orderable: true},
            {data: 'name', name: 'name', orderable: false},
            {data: 'dqc', name: 'dqc', orderable: false},
        ],
        fnInitComplete: function(oSettings, json) {
            $(document).on('hidden.bs.modal', '#ajaxModal', function() {
                $('#committee-table').dataTable().fnReloadAjax(undefined, undefined, true);
            });
           //$('#committee-table_paginate').hide();
           $('#committee-table_length').hide();
           $('.dataTables_filter').hide();
           $('#committee-table_info').appendTo($('#committee-pageinfo'));
           $('#committee-table').removeClass('no-footer');
        },
        "fnDrawCallback": function( oSettings ) {
            $('#committee-pagnination').empty();
            $('.paginate_button').each(function (i) {
                var li = $("<li></li>");
                li.appendTo($('#committee-pagnination'));
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
    var table=$('#committee-table').DataTable();
    $("#searchButton").click(function() {
       table.search($('#searchbox').val()).draw();
    });    
    $("#committee-length-select").change(function() {
        table.page.len($(this).val()).draw();
    });
});
