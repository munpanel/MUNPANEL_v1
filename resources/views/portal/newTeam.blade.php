<div class="modal-dialog">
 <div class="modal-content">
   <header class="header bg-info bg-gradient mp-modal-header">
      <center><h4>新建团队</h4></center>
   </header>
   <div class="modal-body">
     <div class="row">
       <div class="col-sm-12 b-r">
        <form id="newForm" class="form-horizontal" data-validate="parsley" action="{{mp_url('/teams/doCreateTeam')}}" method="post">
            {{csrf_field()}}
                <div class="form-group">
                  <label class="col-sm-2 control-label">团队类型</label>
                  <div class="col-lg-6 btn-group" data-toggle="buttons">
                    <label class="btn btn-sm btn-primary">
                        <input type="radio" name="type" value="school"> <i class="fa fa-check text-active"></i> 中学
                    </label>
                    <label class="btn btn-sm btn-primary">
                        <input type="radio" name="type" value="university"> <i class="fa fa-check text-active"></i> 高等学校
                    </label>
                    <label class="btn btn-sm btn-primary">
                        <input type="radio" name="type" value="team"> <i class="fa fa-check text-active"></i> 团队
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">团队名称</label>
                  <div class="col-sm-10">
                    <input type="text" name="name" class="form-control" placeholder="如 Massachusetts Institute of Technology">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">团队简介</label>
                  <div class="col-sm-10">
                    <input type="text" name="description" class="form-control" placeholder="其他用户将可看到此简介">
                  </div>
                </div>
             <p class="checkbox m-t-lg">
               <a onclick="$('#ajaxModal').modal('hide');$('#ajaxModal').remove();" class="btn btn-sm btn-danger text-uc m-t-n-xs pull-right"><i class="fa fa-times"></i> 取消</a>
               <a onclick="if ($('#newForm').parsley('validate')){loader(this); $('#newForm').submit();}" class="btn btn-sm btn-success text-uc m-t-n-xs m-r-xs pull-right"><i class="fa fa-check"></i> 确定</a>
             </p>
         </form>
       </div>
     </div>
   </div>
 </div><!-- /.modal-content -->
</div>
