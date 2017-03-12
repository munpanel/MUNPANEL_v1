<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            <button id="enableEditable-{{$assignment->id}}" class="btn btn-default pull-right">编辑模式</button><span class="pull-right">&nbsp;</span><button id="deleteButton-{{$assignment->id}}" class="btn btn-danger pull-right">删除</button>
            <h4>学术作业 ID {{$assignment->id}}</h4>
            <table id="assignment-{{$assignment->id}}" class="table table-bordered table-striped" style="clear: both">
                <tbody>
                    <tr>
                        <td width="35%">标题</td>
                        <td width="65%"><a href="#" id="title" data-type="text" data-pk="{{$assignment->id}}" data-url="{{mp_url('/dais/update/assignment/'.$assignment->id)}}" data-title="title" class="editable">{{$assignment->title}}</a></td>
                    </tr>
                    <tr>
                        <td width="35%">上交者</td>
                        <td width="65%"><a href="#" id="type" data-type="select" data-pk="{{$assignment->id}}" data-url="{{mp_url('/dais/update/assignment/'.$assignment->id)}}" data-title="type" data-value='{{$assignment->type}}' data-source="[{value: 'individual', text: '个人'}, {value: 'nation', text: '国家'}]" class="editable"></a></td>
                    </tr>
                    <tr>
                        <td width="35%">上交期限</td>
                        <!-- TODO: 插入日历 -->
                        <td width="65%"><input class="input-sm input-s datepicker-input form-control" type="text" size="16" value="12-02-2016" data-date-format="dd-mm-yyyy"><!--a href="#" id="deadline" data-type="text" data-pk="{{$assignment->id}}" data-url="{{mp_url('/dais/update/assignment/'.$assignment->id)}}" data-title="password" class="editable">{{$assignment->title}}</a--></td>
                    </tr>
                    <tr>         
                        <td width="35%">作业说明</td>
                        <!-- TODO: 在此处使用多行文本框 -->
                        <td width="65%"><a href="#" id="description" data-type="text" data-pk="{{$assignment->id}}" data-url="{{mp_url('/dais/update/assignment/'.$assignment->id)}}" data-title="description" class="editable"></a></td>
                    </tr>
                  </tbody>
            </table>
        </div>
    </div>
</div>
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
    jQuery.get('dais/delete/assignment/{{$assignment->id}}', cb);
});
</script>
