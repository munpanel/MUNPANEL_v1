<div class="modal-dialog">
 <div class="modal-content">
   <header class="header bg-info bg-gradient mp-modal-header">
      <center><h4>管理领队身份</h4></center>
   </header>
   <div class="modal-body">
     <div class="row">
       <div class="col-sm-12 b-r">
        <form id="addAdmin" class="form-horizontal" data-validate="parsley" action="{{mp_url('/teams/doAddAdmin')}}" method="post">
            {{csrf_field()}}
                <div class="form-group">
                  <div class="col-sm-12">
                    <p>请为{{$user->name}}指派新的管理或领队身份。目前共有 {{$confs->count()}} 场会议开放报名。</p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">会议域名</label>
                  <div class="col-sm-10">
                    <input type="text" name="domain" class="form-control" placeholder="*.munpanel.com">
                  </div>
                </div>
             <p class="checkbox m-t-lg">
               <input type="hidden" name="user" value="{{$user->id}}">
               <input type="hidden" name="group" value="{{$gid}}">
               <a onclick="$('#ajaxModal').modal('hide');$('#ajaxModal').remove();" class="btn btn-sm btn-danger text-uc m-t-n-xs pull-right"><i class="fa fa-times"></i> 取消</a>
               <a onclick="if ($('#addAdmin').parsley('validate')){loader(this); $('#addAdmin').submit();}" class="btn btn-sm btn-success text-uc m-t-n-xs m-r-xs pull-right"><i class="fa fa-check"></i> 确定</a>
             </p>
         </form>
       </div>
     </div>
   </div>
 </div><!-- /.modal-content -->
</div>
