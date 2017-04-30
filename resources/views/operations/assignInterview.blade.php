@php
$count = $reg->delegate->interviews()->whereIn('status', ['passed', 'failed'])->count();
@endphp
                <div id="interviewOpSelect" style="display: block;">
                    <h3 class="m-t-sm">安排面试</h3>

                    <p>此代表目前的状态为{{$reg->delegate->statusText()}}，可以为其安排面试。</p>
                    <p>点击<strong>分配面试</strong>按钮后，将会出现可选择的面试官列表，您可以分配一位面试官面试此代表。</p>
                    @if ($count > 1)
                    <p><span class="label label-warning">注意</span> 这将是这位代表的第 {{$count + 1}} 次面试分配。</p>
                    @endif
                    @if ($count == 0)
                    <p>如果此代表具有规定的免试资格，可以以免试通过方式完成此代表的面试流程。点击<strong>免试通过</strong>按钮后，将会出现可选择的面试官列表，您需要分配一位面试官为此代表分配席位。</p>
                    @endif
                    {{-- <p><span class="label label-warning">注意</span> 这位代表的面试安排曾被join("、", $rollback_data); 回退，请在笔记中了解回退原因。</p>--}}

                    <button name="" type="button" class="btn btn-info" onclick="$('#doAssign').show(); $('#interviewOpSelect').hide(); assign_editor.reflow();">分配面试</button>
                    @if ($count == 0)
                    <button name="" type="button" class="btn btn-info" onclick="$('#doExempt').show(); $('#interviewOpSelect').hide(); exempt_editor.reflow();">免试通过</button>
                    @endif

                </div>

                <div id="doAssign" style="display: none;">
                    <h3 class="m-t-sm">分配面试官</h3>

                    {{-- <p><span class="label label-warning">注意</span> 这是二次面试分配。</p>--}}

                    <p>请在此列表中选择面试官，面试官姓名右侧显示了面试官当前分配的未完成面试数量。</p>

                    <form action="{{mp_url('/ot/assignInterview/'.$reg->id)}}" method="post" id="assignInterviewForm">
                    {{csrf_field()}}
                      <input type="hidden" name="id" value="{{$reg->id}}">
                          <div class="m-b">
                          <div class="form-group">
                          <label class="col-sm-2 control-label">面试官</label>
                            <select class="interviewer-list col-sm-10" name="interviewer">
                                @foreach (\App\Interviewer::list() as $name => $group)
                                <optgroup label="{{$name}}">
                                    @foreach ($group as $iid => $iname)
                                    <option value="{{$iid}}">{{$iname}}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                            </div>
                          </div>

                        <div class="form-group">
                          <label class="col-sm-2 control-label">分配备注</label>
                          <input type="hidden" id="assign_notes" name="notes" value="代表不可见... (支持Markdown)">
                          <div id="assign_editor" class="col-lg-10">
                          </div>
                        </div>

                        <p>分配完成之后，MUNPANEL 将自动通知代表和面试官。</p>
                     @if ($count > 0)
                     <div class="form-group">
                       <label>面试选项</label>
                       <br><input name="isRetest" type="checkbox"> 这是高阶面试
                       <br><input name="moveCommittee" type="checkbox"> 将此代表转移至已选面试官所在委员会
                     </div>
                     @endif
                   <button name="submit" type="submit" class="btn btn-info">分配面试官</button>
                   <button name="cancel" type="button" class="btn btn-link" onclick="$('#doAssign').hide(); $('#interviewOpSelect').show();">取消</button>

                   </form>

                </div>

                <div id="doExempt" style="display: none;">
                    <h3 class="m-t-sm">免试指派席位</h3>

                    <p>将会以免试通过方式完成此代表的面试流程，请在此列表中选择面试官，选定的面试官将可以直接为此代表分配席位。</p>

                    <form action="{{mp_url('/ot/exemptInterview/'.$reg->id)}}" method="post" id="exemptInterviewForm">
                    {{csrf_field()}}

                          <div class="m-b">
                          <div class="form-group">
                          <label class="col-sm-2 control-label">面试官</label>
                            <select class="interviewer-list col-sm-10" name="interviewer">
                                @foreach (\App\Interviewer::list() as $name => $group)
                                <optgroup label="{{$name}}">
                                    @foreach ($group as $iid => $iname)
                                    <option value="{{$iid}}">{{$iname}}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                            </div>
                          </div>

                        <div class="form-group">
                          <label class="col-sm-2 control-label">免试备注</label>
                          <input type="hidden" id="exempt_notes" name="notes" value="代表不可见... (支持Markdown)">
                          <div id="exempt_editor" class="col-lg-10">
                          </div>
                        </div>

                        <p>分配完成之后，MUNPANEL 将自动通知代表和面试官。</p>

                   <button name="submit" type="submit" class="btn btn-info">面试通过</button>
                   <button name="cancel" type="button" class="btn btn-link" onclick="$('#doExempt').hide(); $('#interviewOpSelect').show();">取消</button>

                   </form>

                </div>
<script>
$('#assignInterviewForm').submit(function(e){
    e.preventDefault();
    $.post('{{mp_url('/ot/assignInterview/'.$reg->id)}}', $('#assignInterviewForm').serialize())
    $("#ajaxModal").load("{{mp_url('/ot/regInfo.modal/'.$reg->id.'?active=interview')}}");
});
$('#exemptInterviewForm').submit(function(e){
    e.preventDefault();
    $.post('{{mp_url('/ot/exemptInterview/'.$reg->id)}}', $('#exemptInterviewForm').serialize())
    $("#ajaxModal").load("{{mp_url('/ot/regInfo.modal/'.$reg->id.'?active=interview')}}");
});
var assign_editor;
var exempt_editor;
$.getScript( "{{cdn_url('js/markdown/epiceditor.js')}}", function( data, textStatus, jqxhr  ) {
var assign_opts = {
  container: 'assign_editor',
  basePath: '',
  textarea: 'assign_notes',
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
var exempt_opts = {
  container: 'exempt_editor',
  basePath: '',
  textarea: 'exempt_notes',
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
assign_editor = new EpicEditor(assign_opts).load();
exempt_editor = new EpicEditor(exempt_opts).load();
$('#assign_editor').height(80);
$('#exempt_editor').height(80);
});
</script>
