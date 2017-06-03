$(document).ready(function() {
   $('#member-table').DataTable({
        //processing: true,
        //serverSide: true,
        ajax: 'members.ajax',
        columns: [
            {data: 'id', name: 'id', orderable: true},
            {data: 'email', name:'email', orderable: true},
            {data: 'name', name: 'name', orderable: false},
            {data: 'tel', name: 'tel', orderable: false},
            {data: 'admin', name: 'admin', orderable: false}
            //{data: 'type', name: 'type', orderable: true}
        ],
        fnInitComplete: function(oSettings, json) {
            $(document).on('hidden.bs.modal', '#ajaxModal', function() {
                $('#member-table').dataTable().fnReloadAjax(undefined, undefined, true);
            });
           //$('#member-table_paginate').hide();
           $('#member-table_length').hide();
           $('.dataTables_filter').hide();
           $('#member-table_info').appendTo($('#member-pageinfo'));
           $('#member-table').removeClass('no-footer');
        },
        "fnDrawCallback": function( oSettings ) {
            $('#member-pagnination').empty();
            $('.paginate_button').each(function (i) {
                var li = $("<li></li>");
                li.appendTo($('#member-pagnination'));
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
        "order": [[2, "asc"]],
    });
    var table=$('#member-table').DataTable();
    $("#searchButton").click(function() {
       table.search($('#searchbox').val()).draw();
    });    
    $("#member-length-select").change(function() {
        table.page.len($(this).val()).draw();
    });
});
