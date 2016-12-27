$(document).ready(function() {
   $('#assignment-table').DataTable({
        //processing: true,
        //serverSide: true,
        paging: false,
        bFilter: false,
        ajax: 'ajax/assignments',
        columns: [
            {data: 'details', name: 'details', orderable: false},
            {data: 'id', name: 'id', orderable: true},
            {data: 'title', name:'title', orderable: false},
            //{data: 'type', name: 'type', orderable: true},
            {data: 'deadline', name: 'deadline', orderable: true}
            
        ],
        fnInitComplete: function(oSettings, json) {
           $('#assignment-table_length').hide();
           $('#assignment-table_info').appendTo($('#assignment-pageinfo'));
           $('#assignment-table').removeClass('no-footer');
        },
        "language": {
            "zeroRecords": "无作业",
            "info": "共 _MAX_ 项作业",
            "infoEmpty": "无作业",
        },
        "order": [[1, "asc"]],
    });
});
