$(document).ready(function() {
   $('#school-table').DataTable({
        //processing: true,
        //serverSide: true,
        ajax: 'ajax/schools',
        columns: [
            {data: 'details', name: 'details', orderable: false},
            {data: 'id', name: 'id', orderable: true},
            {data: 'name', name: 'name', orderable: false},
            //{data: 'uid', name: 'uid', orderable: true},
            {data: 'statistics', name: 'statistics', orderable: false}
        ],
        fnInitComplete: function(oSettings, json) {
            $(document).on('hidden.bs.modal', '#ajaxModal', function() {
                $('#school-table').dataTable().fnReloadAjax(undefined, undefined, true);
            });
           //$('#school-table_paginate').hide();
           $('#school-table_length').hide();
           $('.dataTables_filter').hide();
           $('#school-table_info').appendTo($('#school-pageinfo'));
           $('#school-table').removeClass('no-footer');
        },
        "fnDrawCallback": function( oSettings ) {
            $('#school-pagnination').empty();
            $('.paginate_button').each(function (i) {
                var li = $("<li></li>");
                li.appendTo($('#school-pagnination'));
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
    var table=$('#school-table').DataTable();
    $("#searchButton").click(function() {
       table.search($('#searchbox').val()).draw();
    });    
    $("#school-length-select").change(function() {
        table.page.len($(this).val()).draw();
    });
});
