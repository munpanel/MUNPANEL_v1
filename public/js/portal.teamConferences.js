$(document).ready(function() {
   $('#conference-table').DataTable({
        processing: true,
        //serverSide: true,
        ajax: 'conferences.ajax',
        columns: [
            {data: 'name', name: 'name', orderable: false},
            {data: 'count', name: 'count', orderable: true}
        ],
        fnInitComplete: function(oSettings, json) {
            $(document).on('hidden.bs.modal', '#ajaxModal', function() {
                $('#conference-table').dataTable().fnReloadAjax(undefined, undefined, true);
            });
           //$('#conference-table_paginate').hide();
           $('#conference-table_length').hide();
           $('.dataTables_filter').hide();
           $('#conference-table_info').appendTo($('#conference-pageinfo'));
           $('#conference-table').removeClass('no-footer');
        },
        "fnDrawCallback": function( oSettings ) {
            $('#conference-pagnination').empty();
            $('.paginate_button').each(function (i) {
                var li = $("<li></li>");
                li.appendTo($('#conference-pagnination'));
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
        "order": [[1, "desc"]],
    });
    var table=$('#conference-table').DataTable();
    $("#searchButton").click(function() {
       table.search($('#searchbox').val()).draw();
    });    
    $("#conference-length-select").change(function() {
        table.page.len($(this).val()).draw();
    });
});
