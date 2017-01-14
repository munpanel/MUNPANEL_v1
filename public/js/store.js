$(document).ready(function() {
   $('#store-table').DataTable({
        //processing: true,
        //serverSide: true,
        paging: false,
        bFilter: false,
        ajax: 'ajax/store',
        columns: [
            {data: 'id', name: 'id', orderable: true},
            {data: 'image', name:'image', orderable: false},
            {data: 'title', name:'title', orderable: false},
            //{data: 'type', name: 'type', orderable: true},
            {data: 'price', name: 'price', orderable: true},
            {data: 'command', name: 'command', orderable: true}            
        ],
        fnInitComplete: function(oSettings, json) {
           $('#store-table_length').hide();
           $('#store-table_info').appendTo($('#store-pageinfo'));
           $('#store-table').removeClass('no-footer');
        },
        "language": {
            "zeroRecords": "无在售商品",
            "info": "共 _MAX_ 项在售商品",
            "infoEmpty": "无在售商品",
        },
        "order": [[1, "asc"]],
    });
});
