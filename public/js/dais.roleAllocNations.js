$(document).ready(function() {
   $('#nation-table').DataTable({
        //processing: true,
        //serverSide: true,
        ajax: 'ajax/roleAllocNations',
        columns: [
            {data: 'select', name: 'select', orderable: false},
            {data: 'name', name: 'name', orderable: false},
            {data: 'nationgroup', name: 'nationgroup', orderable: false},
            {data: 'delegate', name: 'delegate', orderable: false},
            {data: 'command', name: 'command', orderable: false}
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
                $('#nation-table').dataTable().fnReloadAjax(undefined, undefined, true);
            });
           //$('#nation-table_paginate').hide();
           $('#nation-table_length').hide();
           $('.dataTables_filter').hide();
           $('#nation-table_info').appendTo($('#nation-pageinfo'));
           $('#nation-table').removeClass('no-footer');
        },
        "fnDrawCallback": function( oSettings ) {
            $('#nation-pagnination').empty();
            $('.paginate_button').each(function (i) {
                if ($(this).attr('aria-controls') == 'nation-table')
                {
                    var li = $("<li></li>");
                    li.appendTo($('#nation-pagnination'));
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
    var nattable=$('#nation-table').DataTable();
    $("#nation-searchButton").click(function() {
       nattable.search($('#nation-searchBox').val()).draw();
    });    
    $("#nation-length-select").change(function() {
        nattable.page.len($(this).val()).draw();
    });
});