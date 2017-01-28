<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            <button id="enableEditable-{{$nation->id}}" class="btn btn-default pull-right">编辑模式</button><span class="pull-right">&nbsp;</span><button id="deleteButton-{{$nation->id}}" class="btn btn-danger pull-right">删除</button>
            <h4>国家ID {{$nation->id}}</h4>
            <table id="nation-{{$nation->id}}" class="table table-bordered table-striped" style="clear: both">
                <tbody>
                    <tr>
                        <td width="35%">名称</td>
                        <td width="65%"><a href="#" id="name" data-type="text" data-pk="{{$nation->id}}" data-url="{{secure_url('/dais/update/nation/'.$nation->id)}}" data-title="name" class="editable">{{$nation->name}}</a></td>
                    </tr>
                  </tbody>
            </table>
        </div>
    </div>
</div>
<script>
$.fn.editable.defaults.mode = 'inline';
$('#nation-{{$nation->id}} .editable').editable();
$('#nation-{{$nation->id}} .editable').editable('toggleDisabled');

$('#enableEditable-{{$nation->id}}').click(function() {
    $('#nation-{{$nation->id}} .editable').editable('toggleDisabled');
});

$('#deleteButton-{{$nation->id}}').click(function() {
    var cb = function() {
        $('#ajaxModal').modal('hide');
    };
    jQuery.get('dais/delete/nation/{{$nation->id}}', cb);
});
</script>
