<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
                <button id="enableEditable-{{$assignment->id}}" class="btn btn-default pull-right">编辑模式</button><span class="pull-right">&nbsp;</span><button id="deleteButton-{{$assignment->id}}" class="btn btn-danger pull-right">删除</button>
            <h4>{{'学术作业 #' . $assignment->id}}</h4>
            <table id="assignment-{{$assignment->id}}" class="table table-bordered table-striped" style="clear: both">
                <tbody>
                    <tr>
                        <td width="35%">标题</td>
                        <td width="65%"><a href="#" id="title" data-type="text" data-pk="{{$assignment->id}}" data-url="{{mp_url('/ot/update/assignment/'.$assignment->id)}}" data-title="title" class="editable">{{$assignment->title}}</a></td>
                    </tr>
                    <tr>
                        <td width="35%">分发对象</td>
                        <td width="65%">{{$assignment->scope()}}</td>
                    </tr>
            <tr>
                <td width="35%">提交单位</td>
                <td width="65%"><a href="#" id="subject_type" data-type="select" data-pk="{{$assignment->id}}" data-url="{{mp_url('/ot/update/assignment/'.$assignment->id)}}" data-value='{{$assignment->subject_type}}' data-source="[{'value':'individual', 'text':'个人'},{'value':'partner', 'text':'搭档'},{'value':'nation', 'text':'国家'}]" data-title="subject_type" class="editable"></a></td>
            </tr>
            {{-- TODO: 作业类型一经创建不可更改 --}}
            <tr>
                <td width="35%">作业类型</td>
                <td width="65%"><a href="#" id="handin_type" data-type="select" data-pk="{{$assignment->id}}" data-url="{{mp_url('/ot/update/assignment/'.$assignment->id)}}" data-value='{{$assignment->handin_type}}' data-source="[{'value':'upload', 'text':'文件上传'},{'value':'text', 'text':'在线文本编辑器'},{'value':'form', 'text':'在线填写表单'}]" data-title="handin_type" class="editable"></a></td>
            </tr>
                    <tr>
                        <td width="35%">提交期限</td>
                        <td width="65%">{{nicetime($assignment->deadline)}}</td>
                    </tr>
                    <tr>
                        <td width="35%">描述</td>
                        <!-- TODO: 改用多行文本 -->
                        <td width="65%"><a href="#" id="description" data-type="text" data-pk="{{$assignment->id}}" data-url="{{mp_url('/ot/update/assignment/'.$assignment->id)}}" data-title="description" class="editable">{!!$assignment->description!!}</a></td>
                    </tr>
                  </tbody>
            </table>
        </div>
    </div>
</div>
@if (Reg::current()->type == 'ot' || Reg::current()->type == 'dais')
<script>
$.fn.editable.defaults.mode = 'inline';
$('#assignment-{{$assignment->id}} .editable').editable();
$('#assignment-{{$assignment->id}} .editable').editable('toggleDisabled');

$('#enableEditable-{{$assignment->id}}').click(function() {
    $('#assignment-{{$assignment->id}} .editable').editable('toggleDisabled');
});
$('#deleteButton-{{$assignment->id}}').click(function() {
    var cb = function() {
        $('#ajaxModal').modal('hide');
    };
    jQuery.get('ot/delete/assignment/{{$assignment->id}}', cb);
});
</script>
@endif
