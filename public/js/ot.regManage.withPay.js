$(document).ready(function() {
   $('#registration-table').DataTable({
        processing: true,
        //serverSide: true,
        ajax: 'ajax/registrations',
        columns: [
            {data: 'details', name: 'details', orderable: false},
            {data: 'id', name: 'id', orderable: true},
            {data: 'name', name: 'name', orderable: false},
            {data: 'school', name: 'school', orderable: true},
            {data: 'committee', name: 'committee', orderable: true},
            {data: 'partner', name: 'partner', orderable: false},
            {data: 'status', name: 'status', orderable: true}
        ],
        fnInitComplete: function(oSettings, json) {
            $(document).on('change', '.status-options', function() {
                var father = $(this).parent();
                jQuery.get('ot/verify/' + father.attr('uid') + '/' + $(this).val());
                var father = $(this).parent();
                var val = $(this).val();
                father.removeClass();
                if (val == 'reg')
                    father.addClass('has-error');
                else if (val == 'oVerified')
                    father.addClass('has-info');
                else if (val == 'paid')
                    father.addClass('has-success'); 
            });
            $(document).on('hidden.bs.modal', '#ajaxModal', function() {
                $('#registration-table').dataTable().fnReloadAjax(undefined, undefined, true);
            });
           //$('#registration-table_paginate').hide();
           $('#registration-table_length').hide();
           $('.dataTables_filter').hide();
           $('#registration-table_info').appendTo($('#registration-pageinfo'));
           $('#registration-table').removeClass('no-footer');
        },
        "fnDrawCallback": function( oSettings ) {
            $('.status-select').each(function (i) {
                var text = $(this).text();
                //if (text == 'reg' || text == 'sVerified' || text == 'oVerified' || text == 'paid')
                //{
                    var content = $("<select class='status-options form-control m-b' style='height:auto'><option value='reg'>等待学校审核</option><option value='sVerified'>等待组委审核</option><option value='oVerified'>待缴费</option><option value='paid'>成功</option></select>");
                    content.val(text);
                    $(this).empty();
                    $(this).append(content);
                    $(this).removeClass('status-select');
                //}
            });
            $('#registration-pagnination').empty();
            $('.paginate_button').each(function (i) {
                var li = $("<li></li>");
                li.appendTo($('#registration-pagnination'));
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
        "order": [[3, "asc"]],
    });
    var table=$('#registration-table').DataTable();
    $("#searchButton").click(function() {
       table.search($('#searchbox').val()).draw();
    });    
    $("#registration-length-select").change(function() {
        table.page.len($(this).val()).draw();
    });
});
