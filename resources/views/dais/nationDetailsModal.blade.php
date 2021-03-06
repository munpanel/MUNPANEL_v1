<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            <button id="enableEditable-{{$nation->id}}" class="btn btn-default pull-right">编辑模式</button><span class="pull-right">&nbsp;</span><button id="deleteButton-{{$nation->id}}" class="btn btn-danger pull-right">删除</button>
            <h4>国家ID {{$nation->id}}</h4>
            <table id="nation-{{$nation->id}}" class="table table-bordered table-striped" style="clear: both">
                <tbody>
                    @if (Reg::current()->type == 'ot')
                    <tr>
                        <td width="35%">委员会</td>
                        <td width="65%"><a href="#" id="committee_id" data-type="select" data-pk="{{$nation->id}}" data-url="{{mp_url('/dais/update/nation/'.$nation->id)}}" data-title="committee_id" data-value='{{$nation->committee_id}}' data-source="{{$committeesJSON}}" class="editable"></a></td>
                    </tr>
                    @endif
                    <tr>
                        <td width="35%">名称</td>
                        <td width="65%"><a href="#" id="name" data-type="text" data-pk="{{$nation->id}}" data-url="{{mp_url('/dais/update/nation/'.$nation->id)}}" data-title="name" class="editable">{{$nation->name}}</a></td>
                    </tr>
                    <tr>
                        <td width="35%">备注 (职位等)</td>
                        <td width="65%"><a href="#" id="remark" data-type="text" data-pk="{{$nation->id}}" data-url="{{mp_url('/dais/update/nation/'.$nation->id)}}" data-title="remark" class="editable">{{$nation->remark}}</a></td>
                    </tr>
                    <tr>
                        <td width="35%">投票权重 (C)</td>
                        <td width="65%"><a href="#" id="conpetence" data-type="text" data-pk="{{$nation->id}}" data-url="{{mp_url('/dais/update/nation/'.$nation->id)}}" data-title="conpetence" class="editable">{{$nation->conpetence}}</a></td>
                    </tr>
                    <tr>
                        <td width="35%">一票否决权 (VP)</td>
                        <td width="65%"><a href="#" id="veto_power" data-type="select" data-pk="{{$nation->id}}" data-url="{{mp_url('/dais/update/nation/'.$nation->id)}}" data-title="veto_power" data-value='{{$nation->veto_power}}' data-source="[{'value':0, 'text':'否'},{'value':1, 'text':'是'}]" class="editable"></a></td>
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
