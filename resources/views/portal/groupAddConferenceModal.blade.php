<div class="modal-dialog">
 <div class="modal-content">
   <header class="header bg-info bg-gradient mp-modal-header">
      <center><h4>允许报名新的会议</h4></center>
   </header>
   <div class="modal-body">
     <div class="row">
       <div class="col-sm-12 b-r">
        <form id="addConference" class="form-horizontal" data-validate="parsley">
            {{csrf_field()}}
                <div class="form-group">
                  <div class="col-sm-12">
                    <p>只有在此允许后，团队成员才可以团队身份报名该会议</p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">会议域名</label>
                  <div class="col-sm-10">
                    <input type="text" name="domain" class="form-control" placeholder="如xxmun.munpanel.com" data-required="true">
                    <span class="help-block m-b-none">您访问该会议所用的网址（不含https://和后续内容）</span>
                  </div>
                </div>
             <p class="checkbox m-t-lg">
               <a onclick="$('#ajaxModal').modal('hide');$('#ajaxModal').remove();" class="btn btn-sm btn-danger text-uc m-t-n-xs pull-right"><i class="fa fa-times"></i> 取消</a>
               <a onclick="if ($('#addConference').parsley('validate')){loader(this); submitAdmin();}" class="btn btn-sm btn-success text-uc m-t-n-xs m-r-xs pull-right"><i class="fa fa-check"></i> 确定</a>
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
    $.post('{{mp_url('/teams/'.$group->id.'/admin/doAddConference')}}', $('#addConference').serialize()).done(function(data) {
        $.snackbar({content: data});
        $('#ajaxModal').modal('hide');
        $('#ajaxModal').remove();
    });
}
</script>
