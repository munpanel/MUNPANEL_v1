<div class="modal-dialog">
 <div class="modal-content">
   <header class="header bg-info bg-gradient mp-modal-header">
      <center><h4>面试评分</h4></center>
   </header>
   <div class="modal-body">
     <div class="row">
       <div class="col-sm-12 b-r">
        <form id="rateForm" class="form-horizontal" data-validate="parsley" action="{{mp_url('/interview/'.$id.'/rate')}}" method="post">
            {{csrf_field()}}
                <div class="form-group">
                  <label class="col-sm-2 control-label">面试结果</label>
                  <div class="col-lg-6 btn-group" data-toggle="buttons">
                    <label class="btn btn-sm btn-success">
                        <input type="radio" name="result" value="pass"> <i class="fa fa-check text-active"></i> 通过
                    </label>
                    <label class="btn btn-sm btn-danger">
                        <input type="radio" name="result" value="fail" data-required="true"> <i class="fa fa-check text-active"></i> 不通过
                    </label>
                  </div>
                  <label class="col-sm-2 control-label">综合评分</label>
                  <div class="col-sm-2 text-info" id="finalScore"><h4 class="control-inline-h"><strong>未打分</strong></h4></div>
                </div>
                <div class="form-group">
<label class="col-sm-2 control-label">评分</label>
                  <div class="col-lg-10">
@foreach ($scoresOptions->criteria as $key => $value)
                    <div class="btn-group m-b-xs" data-toggle="buttons">
                      <label class="btn btn-sm btn-primary disabled">
                          {{$value->name}}
                      </label>
                      <label class="btn btn-sm btn-primary">
                          <input type="radio" name="{{$key}}" value="0" class="rateRadio"> <i class="fa fa-check text-active"></i> 0
                      </label>
                      <label class="btn btn-sm btn-primary">
                          <input type="radio" name="{{$key}}" value="1" class="rateRadio"> <i class="fa fa-check text-active"></i> 1
                      </label>
                      <label class="btn btn-sm btn-primary">
                          <input type="radio" name="{{$key}}" value="2" class="rateRadio"> <i class="fa fa-check text-active"></i> 2
                      </label>
                      <label class="btn btn-sm btn-primary">
                          <input type="radio" name="{{$key}}" value="3" class="rateRadio"> <i class="fa fa-check text-active"></i> 3
                      </label>
                      <label class="btn btn-sm btn-primary">
                          <input type="radio" name="{{$key}}" value="4" class="rateRadio"> <i class="fa fa-check text-active"></i> 4
                      </label>
                      <label class="btn btn-sm btn-primary">
                          <input type="radio" name="{{$key}}" value="5" class="rateRadio" data-required="true"> <i class="fa fa-check text-active"></i> 5
                      </label>
                    </div>
@endforeach
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">内部反馈</label>
                  <input type="hidden" id="internal_fb" name="internal_fb" value="此内容对被面试者不可见。（支持Markdown）">
                  <div id="internal_editor" class="col-lg-10">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">外部反馈</label>
                  <input type="hidden" id="public_fb" name="public_fb" value="此内容对被面试者可见。（支持Markdown）" data-required="true">
                  <div id="public_editor" class="col-lg-10">
                  </div>
                </div>
             <p class="checkbox m-t-lg">
               <a onclick="$('#ajaxModal').modal('hide');$('#ajaxModal').remove();" class="btn btn-sm btn-danger text-uc m-t-n-xs pull-right"><i class="fa fa-times"></i> 取消</a>
               <a onclick="if ($('#rateForm').parsley('validate')){$('#rateForm').submit();}" class="btn btn-sm btn-success text-uc m-t-n-xs m-r-xs pull-right"><i class="fa fa-check"></i> 确定</a>
             </p>
         </form>
       </div>
     </div>
   </div>
 </div><!-- /.modal-content -->
</div>
<script>
var opts_int = {
  container: 'internal_editor',
  basePath: '',
  textarea: 'internal_fb',
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
};

var opts_ext = {
  container: 'public_editor',
  basePath: '',
  textarea: 'public_fb',
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
};

var editor_int = new EpicEditor(opts_int).load();
var editor_ext = new EpicEditor(opts_ext).load();

$('.rateRadio').change(function(){
score = 0;

@foreach ($scoresOptions->criteria as $key => $value)
score += $("input:radio[name ='{{$key}}']:checked").val() * {{$value->weight}};

@endforeach
score *= {{$scoreOptions->total / 5}};
if (isNaN(score))
    $('#finalScore').html('<h4 class="control-inline-h"><strong>不完整</strong></h4>');
else
    $('#finalScore').html('<h4 class="control-inline-h"><strong>'+score.toFixed(1)+'</strong></h4>');
});

</script>
