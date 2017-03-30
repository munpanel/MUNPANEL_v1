<div class="modal-dialog">
 <div class="modal-content">
   <header class="header bg-success bg-gradient mp-modal-header">
      <center><h4>安排面试</h4></center>
   </header>
   <div class="modal-body">
     <div class="row">
       <div class="col-sm-12 b-r">
        <form id="assignForm" class="form-horizontal" data-validate="parsley" action="{{mp_url('/interview/'.$id.'/arrange')}}" method="post">
            {{csrf_field()}}
                <div class="form-group">
                  <label class="col-sm-2 control-label">面试时间</label>
                  <div class="col-lg-10">
                    <input type="text" name="arrangeTime" class="form-control" data-required="true" id="arrangeTimePicker">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">联络方式</label>
                  <div class="col-lg-10">
                    <select name="typeInterview" class="form-control">
                      <option value="" selected="">请选择</option>
                      <option value="1">电话 (包括备用电话)</option>
                      <option value="2">QQ</option>
                      <option value="3">Skype</option>
                      <option value="4">微信</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">安排备注</label>
                  <input type="hidden" id="notes" name="notes" value="面试方式、面试时要特殊留意的内容等... (支持Markdown)">
                  <div id="epiceditor" class="col-lg-10">
                  </div>
                </div>
             <p class="checkbox m-t-lg">
               <a onclick="$('#ajaxModal').modal('hide');$('#ajaxModal').remove();" class="btn btn-sm btn-danger text-uc m-t-n-xs pull-right"><i class="fa fa-times"></i> 取消</a>
               <a onclick="if ($('#assignForm').parsley('validate')){$('#assignForm').submit();}" class="btn btn-sm btn-success text-uc m-t-n-xs m-r-xs pull-right"><i class="fa fa-check"></i> 安排面试</a>
             </p>
         </form>
       </div>
     </div>
   </div>
 </div><!-- /.modal-content -->
</div>
<script>
$('#arrangeTimePicker').datetimepicker({
    format:'YYYY-MM-DD HH:mm:ss',
    minDate:Date.now()
});
var opts = {
  basePath: '',
  textarea: 'notes',
  clientSideStorage: false,
  button: {fullscreen: false},
  theme: {
    base: 'js/markdown/epiceditor.css',
    preview: 'js/markdown/bartik.css',
    editor: 'js/markdown/epic-light.css'
  },
  file: {
      name: '',
      autoSave: false
  }
}

var editor = new EpicEditor(opts).load();
</script>
