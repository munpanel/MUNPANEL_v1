$(document).ready(function() {
   $('#document-table').DataTable({
        //processing: true,
        //serverSide: true,
        paging: false,
        bFilter: false,
        ajax: 'ajax/documents',
        columns: [
            {data: 'details', name: 'details', orderable: false},
            {data: 'id', name: 'id', orderable: true},
            {data: 'title', name:'title', orderable: false},
            //{data: 'type', name: 'type', orderable: true},
            {data: 'deadline', name: 'deadline', orderable: true}
            
        ],
        fnInitComplete: function(oSettings, json) {
           $('#document-table_length').hide();
           $('#document-table_info').appendTo($('#document-pageinfo'));
           $('#document-table').removeClass('no-footer');
        },
        "language": {
            "zeroRecords": "无文件",
            "info": "共 _MAX_ 项文件",
            "infoEmpty": "无文件",
        },
        "order": [[1, "asc"]],
    });
});
