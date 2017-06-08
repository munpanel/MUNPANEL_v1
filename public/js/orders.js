$(document).ready(function() {
   $('#store-table').DataTable({
        //processing: true,
        //serverSide: true,
        paging: false,
        bFilter: false,
        ajax: '/ajax/orders',
        columns: [
            {data: 'details', name: 'details', orderable: false},
            {data: 'id', name: 'id', orderable: false},
            {data: 'price', name: 'price', orderable: true},
            {data: 'status', name: 'status', orderable: true},
            {data: 'time', name: 'time', orderable: true}            
        ],
        fnInitComplete: function(oSettings, json) {
           $('#store-table_length').hide();
           $('#store-table_info').appendTo($('#store-pageinfo'));
           $('#store-table').removeClass('no-footer');
        },
        "language": {
            "zeroRecords": "无订单",
            "info": "共 _MAX_ 项订单",
            "infoEmpty": "无订单",
        },
        "order": [[4, "asc"]],
    });
});
