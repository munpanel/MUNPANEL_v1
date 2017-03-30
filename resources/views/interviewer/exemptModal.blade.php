<div class="modal-dialog">
 <div class="modal-content">
   @if ($mode == 'exempt')
   <header class="header bg-info bg-gradient mp-modal-header">
      <center><h4>免试通过</h4></center>
   </header>
   @elseif ($mode == 'rollback')
   <header class="header bg-warning bg-gradient mp-modal-header">
      <center><h4>退回面试</h4></center>
   </header>
   @endif
   <div class="modal-body">
     <div class="row">
       <div class="col-sm-12 b-r">
        @if ($mode == 'exempt')
        <form id="assignForm" class="form-horizontal" data-validate="parsley" action="{{mp_url('/interview/'.$id.'/exempt')}}" method="post">
        <input type="hidden" name="mode" value="exempt">
        @elseif ($mode == 'rollback')
        <form id="assignForm" class="form-horizontal" data-validate="parsley" action="{{mp_url('/interview/'.$id.'/rollBack')}}" method="post">
        <input type="hidden" name="mode" value="rollback">
        @else
        <form id="assignForm" class="form-horizontal" data-validate="parsley" method="post">
        @endif
            {{csrf_field()}}
                <div class="form-group">
                  <label class="col-sm-2 control-label">安排备注</label>
                  <input type="hidden" id="notes" name="notes" value="任何说明... (支持Markdown)">
                  <div id="epiceditor" class="col-lg-10">
                  </div>
                </div>
           <p class="checkbox m-t-lg">
             <a onclick="$('#ajaxModal').modal('hide');$('#ajaxModal').remove();" class="btn btn-sm btn-danger text-uc m-t-n-xs pull-right"><i class="fa fa-times"></i> 取消</a>
             @if ($mode == 'exempt')
             <button type="submit" class="btn btn-sm btn-success text-uc m-t-n-xs m-r-xs pull-right"><i class="fa fa-check"></i> 面试通过</button>
             @elseif ($mode == 'rollback')
             <button type="submit" class="btn btn-sm btn-success text-uc m-t-n-xs m-r-xs pull-right"><i class="fa fa-check"></i> 退回面试</button>
             @endif
           </p>
         </form>
       </div>
     </div>
   </div>
 </div><!-- /.modal-content -->
</div>
<script>
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

