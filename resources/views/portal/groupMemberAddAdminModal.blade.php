<div class="modal-dialog">
 <div class="modal-content">
   <header class="header bg-info bg-gradient mp-modal-header">
      <center><h4>管理身份添加</h4></center>
   </header>
   <div class="modal-body">
     <div class="row">
       <div class="col-sm-12 b-r">
        <form id="addAdmin" class="form-horizontal" data-validate="parsley" action="{{mp_url('/teams/doAddAdmin')}}" method="post">
            {{csrf_field()}}
                <div class="form-group">
                  <div class="col-sm-12">
                    <p>请为{{$user->name}}指派新的全局或会议管理身份。</p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">会议域名</label>
                  <div class="col-sm-10">
                    <input type="text" name="domain" class="form-control" placeholder="如xxmun.munpanel.com">
                    <span class="help-block m-b-none">您访问该会议所用的网址（不含https://和后续内容）；如需添加全局管理，此处填写“global”</span>
                  </div>
                </div>
             <p class="checkbox m-t-lg">
               <input type="hidden" name="user" value="{{$user->id}}">
               <input type="hidden" name="group" value="{{$group->id}}">
               <a onclick="$('#ajaxModal').modal('hide');$('#ajaxModal').remove();" class="btn btn-sm btn-danger text-uc m-t-n-xs pull-right"><i class="fa fa-times"></i> 取消</a>
               <a onclick="if ($('#addAdmin').parsley('validate')){loader(this); submitAdmin();}" class="btn btn-sm btn-success text-uc m-t-n-xs m-r-xs pull-right"><i class="fa fa-check"></i> 确定</a>
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
    $.post('{{mp_url('/teams/doAddAdmin')}}', $('#addAdmin').serialize()).done(function(data) {
        $.snackbar({content: data});
        $('#ajaxModal').modal('hide');
        $('#ajaxModal').remove();
    });
}
</script>
