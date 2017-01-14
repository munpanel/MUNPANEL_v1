$(document).ready(function() {
   $('#shop-table').DataTable({
        //processing: true,
        //serverSide: true,
        paging: false,
        bFilter: false,
        ajax: 'ajax/shops',
        columns: [
            {data: 'id', name: 'id', orderable: true},
            {data: 'image', name:'image', orderable: false},
            {data: 'title', name:'title', orderable: false},
            //{data: 'type', name: 'type', orderable: true},
            {data: 'price', name: 'price', orderable: true},
            {data: 'command', name: 'command', orderable: true}            
        ],
        fnInitComplete: function(oSettings, json) {
           $('#shop-table_length').hide();
           $('#shop-table_info').appendTo($('#shop-pageinfo'));
           $('#shop-table').removeClass('no-footer');
        },
        "language": {
            "zeroRecords": "无在售商品",
            "info": "共 _MAX_ 项在售商品",
            "infoEmpty": "无在售商品",
        },
        "order": [[1, "asc"]],
    });
});
