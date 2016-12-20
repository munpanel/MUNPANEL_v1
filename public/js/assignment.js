$(document).ready(function() {
   $('#assignment-table').DataTable({
        //processing: true,
        //serverSide: true,
        ajax: 'ajax/assignments',
        columns: [
            {data: 'id', name: 'id', orderable: true},
            {data: 'details', name: 'details', orderable: false},
            {data: 'title', name:'title', orderable: false},
            //{data: 'type', name: 'type', orderable: true},
            {data: 'deadline', name: 'deadline', orderable: true}
            
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
            
            $(document).on('hidden.bs.modal', '#ajaxModal', function() {
                $('#assignment-table').dataTable().fnReloadAjax(undefined, undefined, true);
            });
           /*$('#assignment-table_paginate').hide();
           $('#assignment-table_length').hide();
           $('.dataTables_filter').hide();
           $('#assignment-table_info').appendTo($('#assignment-pageinfo'));
           $('#assignment-table').removeClass('no-footer');
        },/*
        "fnDrawCallback": function( oSettings ) {
            $('#assignment-pagnination').empty();
            $('.paginate_button').each(function (i) {
                var li = $("<li></li>");
                li.appendTo($('#assignment-pagnination'));
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
        "order": [[1, "asc"]],*/
    });
    var table=$('#assignment-table').DataTable();
    $("#assignment-length-select").change(function() {
        table.page.len($(this).val()).draw();
    });
});
