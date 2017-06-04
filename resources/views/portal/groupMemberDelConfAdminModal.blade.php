<div class="modal-dialog">
 <div class="modal-content">
   <header class="header bg-info bg-gradient mp-modal-header">
      <center><h4>管理身份删除</h4></center>
   </header>
   <div class="modal-body">
     <div class="row">
       <div class="col-sm-12 b-r">
        <form id="delAdmin" class="form-horizontal" data-validate="parsley" action="{{mp_url('/teams/doDelAdmin')}}" method="post">
            {{csrf_field()}}
                <div class="form-group">
                  <div class="col-sm-12">
                    <p>{{$user->name}}共有如下管理身份：</p>
                    @foreach($admins as $admin)
                    {{$admin->id}}.{{$admin->conference->name}}<br>
                    @endforeach
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">ID</label>
                  <div class="col-sm-10">
                    <input type="text" name="reg" class="form-control">
                    <span class="help-block m-b-none">您希望删除的管理身份ID</span>
                  </div>
                </div>
             <p class="checkbox m-t-lg">
               <input type="hidden" name="user" value="{{$user->id}}">
               <input type="hidden" name="group" value="{{$group->id}}">
               <a onclick="$('#ajaxModal').modal('hide');$('#ajaxModal').remove();" class="btn btn-sm btn-danger text-uc m-t-n-xs pull-right"><i class="fa fa-times"></i> 取消</a>
               <a onclick="if ($('#delAdmin').parsley('validate')){loader(this); submitAdmin();}" class="btn btn-sm btn-success text-uc m-t-n-xs m-r-xs pull-right"><i class="fa fa-check"></i> 确定</a>
             </p>
         </form>
       </div>
     </div>
   </div>
 </div><!-- /.modal-content -->
</div>
<script>
function submitAdmin()
{
    $.post('{{mp_url('/teams/doDelAdmin')}}', $('#delAdmin').serialize()).done(function(data) {
        $.snackbar({content: data});
        $('#ajaxModal').modal('hide');
        $('#ajaxModal').remove();
    });
}
</script>
