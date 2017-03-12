<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            <button id="enableEditable-{{$committee->id}}" class="btn btn-default pull-right">编辑模式</button><span class="pull-right">&nbsp;</span><button id="deleteButton-{{$committee->id}}" class="btn btn-danger pull-right">删除</button>
            <h4>委员会ID {{$committee->id}}</h4>
            <table id="committee-{{$committee->id}}" class="table table-bordered table-striped" style="clear: both">
                <tbody>
                    <tr>
                        <td width="35%">名称</td>
                        <td width="65%"><a href="#" id="name" data-type="text" data-pk="{{$committee->id}}" data-url="{{mp_url('/ot/update/committee/'.$committee->id)}}" data-title="name" class="editable">{{$committee->name}}</a></td>
                    </tr>
                  </tbody>
            </table>
        </div>
    </div>
</div>
<script>
$.fn.editable.defaults.mode = 'inline';
$('#committee-{{$committee->id}} .editable').editable();
$('#committee-{{$committee->id}} .editable').editable('toggleDisabled');

$('#enableEditable-{{$committee->id}}').click(function() {
    $('#committee-{{$committee->id}} .editable').editable('toggleDisabled');
});

$('#deleteButton-{{$committee->id}}').click(function() {
    var cb = function() {
        $('#ajaxModal').modal('hide');
    };
    jQuery.get('ot/delete/committee/{{$committee->id}}', cb);
});
</script>
