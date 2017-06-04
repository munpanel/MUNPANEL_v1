<div class="modal-dialog">
 <div class="modal-content">
   <header class="header bg-info bg-gradient mp-modal-header">
      <center><h4>管理身份清空</h4></center>
   </header>
   <div class="modal-body">
     <div class="row">
       <div class="col-sm-12 b-r">
        <form id="delAdmin" class="form-horizontal" data-validate="parsley" action="{{mp_url('/teams/doDelAdmin')}}" method="post">
            {{csrf_field()}}
                <div class="form-group">
                  <div class="col-sm-12">
                    <p>{{$user->name}}为{{$group->name}}的全局管理，这将清空他的所有管理身份，是否确定？</p>
                  </div>
                </div>
             <p class="checkbox m-t-lg">
               <input type="hidden" name="user" value="{{$user->id}}">
               <input type="hidden" name="group" value="{{$group->id}}">
               <input type="hidden" name="reg" value="all">
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
