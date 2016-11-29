$(document).ready(function() {
   $('#registration-table').DataTable({
        //processing: true,
        //serverSide: true,
        ajax: 'ajax/registrations',
        columns: [
            {data: 'details', name: 'details', orderable: false},
            {data: 'name', name: 'name', orderable: false},
            {data: 'school', name: 'school', orderable: true},
            {data: 'committee', name: 'committee', orderable: true},
            {data: 'partner', name: 'partner', orderable: false},
            {data: 'status', name: 'status', orderable: true}
        ],
        fnInitComplete: function(oSettings, json) {
            $(document).on('click','.details-modal', function(e) {
                $('#ajaxModal').remove();
                e.preventDefault();
                var $this = $(this)
                  , $remote = $this.data('remote') || $this.attr('href')
                  , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
                $('body').append($modal);
                $modal.modal();
                $modal.load($remote);
            });
           //$('#registration-table_paginate').hide();
           $('#registration-table_length').hide();
           $('.dataTables_filter').hide();
           $('#registration-table_info').appendTo($('#registration-pageinfo'));
           $('#registration-table').removeClass('no-footer');
        },
        "fnDrawCallback": function( oSettings ) {
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
