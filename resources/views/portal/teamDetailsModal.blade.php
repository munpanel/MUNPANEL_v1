<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            @if($isAdmin)
            <button id="enableEditable-{{$school->id}}" class="btn btn-default pull-right">编辑模式</button><span class="pull-right">&nbsp;</span><a href="{{mp_url('/teams/'.$school->id.'/admin')}}" class="btn btn-warning pull-right">控制面板</a>
            @endif
            <h4>团队ID {{$school->id}} - 基本信息</h4>
            <table id="school-{{$school->id}}" class="table table-bordered table-striped" style="clear: both">
                <tbody>
                    <tr>
                        <td width="35%">名称</td>
                        <td width="65%"><a href="#" id="name" data-type="text" data-pk="{{$school->id}}" data-url="{{mp_url('/teams/'.$school->id.'/doUpdate')}}" data-title="name" class="editable">{{$school->name}}</a></td>
                    </tr>
                    <tr>
                        <td width="35%">类型</td>
                        <td width="65%"><a href="#" id="type" data-type="select" data-pk="{{$school->id}}" data-url="{{mp_url('/teams/'.$school->id.'/doUpdate')}}" data-title="type" data-value='{{$school->type}}' data-source="[{'value':'school', 'text':'中学'},{'value':'university', 'text':'高等学校'}, {'value':'group', text:'团体'}]" class="editable"></a></td>
                    </tr>
                    <tr>
                        <td width="35%">简介</td>
                        <td width="65%"><a href="#" id="description" data-type="textarea" data-pk="{{$school->id}}" data-url="{{mp_url('/teams/'.$school->id.'/doUpdate')}}" data-title="name" class="editable">{{$school->description}}</a></td>
                    </tr>
                  </tbody>
            </table>
        </div>
    </div>
</div>
@if ($isAdmin)
<script>
$.fn.editable.defaults.mode = 'inline';
$('#school-{{$school->id}} .editable').editable();
$('#school-{{$school->id}} .editable').editable('toggleDisabled');

$('#enableEditable-{{$school->id}}').click(function() {
    $('#school-{{$school->id}} .editable').editable('toggleDisabled');
});

</script>
@endif
