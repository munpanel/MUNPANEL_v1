<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            <h4>UID {{$user->id}}</h4><button id="enableEditable-{{$user->id}}" class="btn btn-default pull-right">编辑模式</button>
            <table id="user-{{$user->id}}" class="table table-bordered table-striped" style="clear: both">
                <tbody>
                    <tr>
                        <td width="35%">email</td>
                        <td width="65%"><a href="#" id="email" data-type="text" data-pk="{{$user->id}}" data-url="{{secure_url('/ot/update/user/'.$user->id)}}" data-title="email" class="editable">{{$user->email}}</a></td>
                    </tr>
                    <tr>
                        <td width="35%">姓名</td>
                        <td width="65%"><a href="#" id="name" data-type="text" data-pk="{{$user->id}}" data-url="{{secure_url('/ot/update/user/'.$user->id)}}" data-title="name" class="editable">{{$user->name}}</a></td>
                    </tr>
                    <tr>
                        <td width="35%">密码</td>
                        <td width="65%"><a href="#" id="password" data-type="password" data-pk="{{$user->id}}" data-url="{{secure_url('/ot/update/user/'.$user->id)}}" data-title="password" class="editable">已加密</a></td>
                    </tr>
                    <tr>         
                        <td width="35%">类型(修改将删除报名数据)</td>
                        <td width="65%"><a href="#" id="type" data-type="select" data-pk="{{$user->id}}" data-url="{{secure_url('/ot/update/user/'.$user->id)}}" ata-title="type" data-value='{{$user->type}}' data-source="[{value: 'unregistered', text: '未报名'}, {value: 'ot', text: '组织团队'}, {value: 'dais', text: '学术团队'}, {value: 'delegate', text: '代表'}, {value: 'volunteer', text: '志愿者'}, {value: 'observer', text: '观察员'}, {value: 'school', text: '学校'}]" class="editable"></a></td>
                    </tr>
                  </tbody>
            </table>
        </div>
    </div>
</div>
<script>
$.fn.editable.defaults.mode = 'inline';
$('#user-{{$user->id}} .editable').editable();
$('#user-{{$user->id}} .editable').editable('toggleDisabled');

$('#enableEditable-{{$user->id}}').click(function() {
    $('#user-{{$user->id}} .editable').editable('toggleDisabled');
});
</script>
