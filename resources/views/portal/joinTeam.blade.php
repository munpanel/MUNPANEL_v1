<div class="modal-dialog">
 <div class="modal-content">
   <header class="header bg-info bg-gradient mp-modal-header">
      <center><h4>加入团队</h4></center>
   </header>
   <div class="modal-body">
     <div class="row">
       <div class="col-sm-12 b-r">
        <form id="joinForm" class="form-horizontal" data-validate="parsley" action="{{mp_url('/teams/doJoinTeam')}}" method="post">
            {{csrf_field()}}
                <div class="form-group">
                  <label class="col-sm-2 control-label">团队邀请码</label>
                  <div class="col-sm-10">
                    <input type="text" name="code" class="form-control" placeholder="由您的团队管理员提供" data-required="true">
                  </div>
                </div>
             <p class="checkbox m-t-lg">
               <a onclick="$('#ajaxModal').modal('hide');$('#ajaxModal').remove();" class="btn btn-sm btn-danger text-uc m-t-n-xs pull-right"><i class="fa fa-times"></i> 取消</a>
               <a onclick="if ($('#joinForm').parsley('validate')){loader(this); $('#joinForm').submit();}" class="btn btn-sm btn-success text-uc m-t-n-xs m-r-xs pull-right"><i class="fa fa-check"></i> 确定</a>
             </p>
         </form>
       </div>
     </div>
   </div>
 </div><!-- /.modal-content -->
</div>
