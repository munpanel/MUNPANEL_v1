<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            @if (Auth::user()->type == 'ot' || Auth::user()->type == 'dais') 
                <button id="enableEditable-{{$document->id}}" class="btn btn-default pull-right">编辑模式</button><span class="pull-right">&nbsp;</span><button id="deleteButton-{{$document->id}}" class="btn btn-danger pull-right">删除</button>
            @endif
            <h4>{{(Auth::user()->type == 'ot' || Auth::user()->type == 'dais') ? '学术文件 #' . $document->id : $document->title}}</h4>
            <table id="document-{{$document->id}}" class="table table-bordered table-striped" style="clear: both">
                <tbody>
                    @if (Auth::user()->type == 'ot' || Auth::user()->type == 'dais')
                    <tr>
                        <td width="35%">标题</td>
                        <td width="65%"><a href="#" id="title" data-type="text" data-pk="{{$document->id}}" data-url="{{secure_url('/ot/update/document/'.$document->id)}}" data-title="title" class="editable">{{$document->title}}</a></td>
                    </tr>
                    @endif
                    <tr>
                        <td width="35%">分发对象</td>
                        <td width="65%">{{$document->scope()}}</td>
                    </tr>
                    <tr>
                        <td width="35%">创建日期</td>
                        <td width="65%">{{$document->created_at}}</td>
                    </tr>
                    <tr>
                        <td width="35%">描述</td>
                        @if (Auth::user()->type == 'ot' || Auth::user()->type == 'dais')
                        <!-- TODO: 改用多行文本 -->
                        <td width="65%"><a href="#" id="description" data-type="text" data-pk="{{$document->id}}" data-url="{{secure_url('/ot/update/document/'.$document->id)}}" data-title="description" class="editable">{!!$document->description!!}</a></td>
                        @else
                        <td width="65%">{!!$document->description!!}</td>
                        @endif
                    </tr>
                    <tr>         
                        <td width="35%">统计信息</td>
                        <td width="65%">{{$document->views}} 次阅览，{{$document->downloads}} 次下载</td>
                    </tr>
                    @if (Auth::user()->type == 'ot' || Auth::user()->type == 'dais')
                    <tr>         
                        <td width="35%">选择文件 (PDF 格式)</td>
                        <td width="65%">{{$document->path}}<br>TODO: 文件选择器<br>TODO: 上传后重置统计信息</td>
                    </tr>
                    @endif
                  </tbody>
            </table>
        </div>
    </div>
</div>
@if (Auth::user()->type == 'ot' || Auth::user()->type == 'dais')
<script>
$.fn.editable.defaults.mode = 'inline';
$('#document-{{$document->id}} .editable').editable();
$('#document-{{$document->id}} .editable').editable('toggleDisabled');

$('#enableEditable-{{$document->id}}').click(function() {
    $('#document-{{$document->id}} .editable').editable('toggleDisabled');
});
$('#deleteButton-{{$document->id}}').click(function() {
    var cb = function() {
        $('#ajaxModal').modal('hide');
    };
    jQuery.get('ot/delete/document/{{$document->id}}', cb);
});
</script>
@endif
