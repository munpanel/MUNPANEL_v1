$(document).ready(function() {
   $('#team-table').DataTable({
        //processing: true,
        //serverSide: true,
        ajax: 'ajax/teams',
        columns: [
            {data: 'details', name: 'details', orderable: false},
            {data: 'id', name: 'id', orderable: true},
            {data: 'type', name: 'type', orderable: true},
            {data: 'name', name: 'name', orderable: false},
            {data: 'admin', name: 'admin', orderable: true},
        ],
        fnInitComplete: function(oSettings, json) {
            $(document).on('hidden.bs.modal', '#ajaxModal', function() {
                $('#team-table').dataTable().fnReloadAjax(undefined, undefined, true);
            });
           //$('#team-table_paginate').hide();
           $('#team-table_length').hide();
           $('.dataTables_filter').hide();
           $('#team-table_info').appendTo($('#team-pageinfo'));
           $('#team-table').removeClass('no-footer');
        },
        "fnDrawCallback": function( oSettings ) {
            $('#team-pagnination').empty();
            $('.paginate_button').each(function (i) {
                var li = $("<li></li>");
                li.appendTo($('#team-pagnination'));
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
    var table=$('#team-table').DataTable();
    $("#searchButton").click(function() {
       table.search($('#searchbox').val()).draw();
    });    
    $("#team-length-select").change(function() {
        table.page.len($(this).val()).draw();
    });
});
