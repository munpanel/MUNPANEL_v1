<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            <h4>学校ID {{$school->id}}</h4><button id="enableEditable-{{$school->id}}" class="btn btn-default pull-right">编辑模式</button><button id="deleteButton-{{$school->id}}" class="btn btn-danger pull-right">删除</button>

            <table id="school-{{$school->id}}" class="table table-bordered table-striped" style="clear: both">
                <tbody>
                    <tr>
                        <td width="35%">名称</td>
                        <td width="65%"><a href="#" id="name" data-type="text" data-pk="{{$school->id}}" data-url="{{secure_url('/ot/update/school/'.$school->id)}}" data-title="name" class="editable">{{$school->name}}</a></td>
                    </tr>
                    <tr>
                        <td width="35%">管理员UID</td>
                        <td width="65%"><a href="#" id="user_id" data-type="text" data-pk="{{$school->id}}" data-url="{{secure_url('/ot/update/school/'.$school->id)}}" data-title="user_id" class="editable">{{$school->user_id}}</a></td>
                    </tr>
                  </tbody>
            </table>
        </div>
    </div>
</div>
<script>
$.fn.editable.defaults.mode = 'inline';
$('#school-{{$school->id}} .editable').editable();
$('#school-{{$school->id}} .editable').editable('toggleDisabled');

$('#enableEditable-{{$school->id}}').click(function() {
    $('#school-{{$school->id}} .editable').editable('toggleDisabled');
});
$('#deleteButton-{{$school->id}}').click(function() {
    var cb = function() {
        $('#ajaxModal').modal('hide');
    };
    jQuery.get('ot/delete/school/{{$school->id}}', cb);
});
</script>
