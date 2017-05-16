<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            <button id="enableEditable-{{$interview->id}}" class="btn btn-default pull-right">编辑模式</button><span class="pull-right">&nbsp;</span>
            <h4>面试ID {{$interview->id}}</h4>
            <div class="alert alert-warning"><b>请慎用此功能，正常情况下请使用面试官账号操作。此编辑功能仅供面试官操作错误时修复原始数据库数据用；修改将不会发送任何短信通知。如需编辑评分分数等内容，请修改面试状态并通过面试官账号重新打分。编辑成功后刷新面试列表即可查看编辑结果</b></div>
            <table id="interview-{{$interview->id}}" class="table table-bordered table-striped" style="clear: both">
                <tbody>
                    <tr>
                        <td width="35%">面试状态</td>
                        <td width="65%"><a href="#" id="status" data-type="select" data-pk="{{$interview->id}}" data-url="{{mp_url('/ot/update/interview/'.$interview->id)}}" data-title="status" data-value='{{$interview->status}}' data-source="[{value: 'assigned', text: '已分配'}, {value: 'arranged', text: '已安排'}, {value: 'cancelled', text: '已取消'}, {value: 'passed', text: '已通过'}, {value: 'failed', text: '未通过'}, {value: 'exempted', text: '免试通过'}, {value: 'undecided', text: '待定'}]" class="editable"></a></td>
                    </tr>
                    <tr>
                        <td width="35%">面试官ID</td>
                        <td width="65%"><a href="#" id="interviewer_id" data-type="number" data-pk="{{$interview->id}}" data-url="{{mp_url('/ot/update/interview/'.$interview->id)}}" data-title="Interviewer ID" class="editable">{{$interview->interviewer_id}}</a></td>
                    </tr>
                    <tr>
                        <td width="35%">内部评价</td>
                        <td width="65%"><a href="#" id="internal_fb" data-type="textarea" data-pk="{{$interview->id}}" data-url="{{mp_url('/ot/update/interview/'.$interview->id)}}" data-title="Internal Feedback" class="editable">{{$interview->internal_fb}}</a></td>
                    </tr>
                    <tr>
                        <td width="35%">公开评价</td>
                        <td width="65%"><a href="#" id="public_fb" data-type="textarea" data-pk="{{$interview->id}}" data-url="{{mp_url('/ot/update/interview/'.$interview->id)}}" data-title="Public Feedback" class="editable">{{$interview->public_fb}}</a></td>
                    </tr>
                  </tbody>
            </table>
        </div>
    </div>
</div>
<script>
$.fn.editable.defaults.mode = 'inline';
$('#interview-{{$interview->id}} .editable').editable();
{{--$('#u-{{$interview->id}} .editable').editable('toggleDisabled');--}}

$('#enableEditable-{{$interview->id}}').click(function() {
    $('#interview-{{$interview->id}} .editable').editable('toggleDisabled');
});
{{--
$('#deleteButton-{{$interview->id}}').click(function() {
    var cb = function() {
        $('#ajaxModal').modal('hide');
    };
    jQuery.get('ot/delete/interview/{{$interview->id}}', cb);
});
--}}
</script>
