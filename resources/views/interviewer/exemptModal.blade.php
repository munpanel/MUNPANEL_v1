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
   @elseif ($mode == 'cancel')
   <header class="header bg-warning bg-gradient mp-modal-header">
      <center><h4>取消面试</h4></center>
   </header>
   @elseif ($mode == 'rate')
   <header class="header bg-info bg-gradient mp-modal-header">
      <center><h4>为面试评分</h4></center>
   </header>
   @endif
   <div class="modal-body">
     <div class="row">
       <div class="col-sm-12 b-r">
        @if (!empty($mode))
        <form id="assignForm" class="form-horizontal" data-validate="parsley" action="{{mp_url('/interview/'.$id.'/{{$mode}}')}}" method="post">
        @else
        <form id="assignForm" class="form-horizontal" data-validate="parsley" method="post">
        @endif
        <input type="hidden" name="mode" value="{{$mode}}">
            {{csrf_field()}}
            @if ($mode == 'rate')
                <div class="form-group">
                  <label class="col-sm-2 control-label">面试结果</label>
                  <div class="col-lg-10"><span class="pull-right">                    
                  <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-sm btn-success" id="btn-pass" onclick="">
                      <input name="result" id="pass" type="radio" value="pass" data-required="true"> <i class="fa fa-check text-active"></i>通过
                    </label>
                    <label class="btn btn-sm btn-danger" id="btn-fail" onclick="$('input#fail').checked = true;">
                      <input name="result" id="fail" type="radio" value="fail"> <i class="fa fa-times text-active"></i>不通过
                    </label>
                  </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">评分</label>
                  <div class="col-sm-8">
                  <input class="slider" type="text" value="" data-slider-min="0" data-slider-max="10" data-slider-step="0.1" data-slider-value="0">
                  </div>
                  <div class="col-sm-2"><span class="pull-right"><h4><strong id="myscore" class="text-success">0.0</strong></h4></span></div>
                </div>
            @endif
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{$mode == 'rate' ? '反馈' : '安排备注'}}</label>
                  <input type="hidden" id="notes" name="notes" value="对面试的文本评价... (对代表可见，支持Markdown)">
                  <div id="epiceditor" class="col-lg-10">
                  </div>
                </div>
            @if ($mode == 'rate')
                <div class="form-group">
                  <label class="col-sm-2 control-label">内部反馈</label>
                  <input type="hidden" id="fb_int" name="fb_int" value="内部评价或其他任何说明... (对代表不可见，支持Markdown)">
                  <div id="epiceditor1" class="col-lg-10">
                  </div>
                </div>
            @endif
           <p class="checkbox m-t-lg">
             <a onclick="$('#ajaxModal').modal('hide');$('#ajaxModal').remove();" class="btn btn-sm btn-white text-uc text-danger m-t-n-xs pull-right"><i class="fa fa-times"></i> 取消</a>
             @if ($mode == 'exempt')
             <button type="submit" class="btn btn-sm btn-success text-uc m-t-n-xs m-r-xs pull-right"><i class="fa fa-check"></i> 面试通过</button>
             @elseif ($mode == 'rollback')
             <button type="submit" class="btn btn-sm btn-success text-uc m-t-n-xs m-r-xs pull-right"><i class="fa fa-check"></i> 退回面试</button>
             @elseif ($mode == 'cancel')
             <button type="submit" class="btn btn-sm btn-success text-uc m-t-n-xs m-r-xs pull-right"><i class="fa fa-check"></i> 取消面试</button>
             @elseif ($mode == 'rate')
             <button type="submit" id="ratesubmit" class="btn btn-sm btn-success text-uc m-t-n-xs m-r-xs pull-right"><i class="fa fa-check"></i> 通过面试</button>
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
  container: 'epiceditor',
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
@if ($mode == 'rate')
var opt1 = {
  container: 'epiceditor1',
  basePath: '',
  textarea: 'fb_int',
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
var editor = new EpicEditor(opt1).load();
$('#btn-pass').click(function(e) {
  $('input#pass').checked = true;
  $('button#ratesubmit').classList.remove('btn-danger');
  $('button#ratesubmit').classList.add('btn-success');
  $('button#ratesubmit').innerHTML = '<i class="fa fa-check"></i> 通过面试';
});
$('#btn-fail').click(function(e) {
  $('input#fail').checked = true;
  $('button#ratesubmit').classList.remove('btn-success');
  $('button#ratesubmit').classList.add('btn-danger');
  $('button#ratesubmit').innerHTML = '<i class="fa fa-check"></i> 不通过面试';
});
@endif
</script>