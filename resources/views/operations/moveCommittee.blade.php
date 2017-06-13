                <div id="ot_changecmt" style="display: block;">
                    <h3 class="m-t-sm">变更委员会</h3>
                    <p>该代表已选定委员会<strong>{{$reg->delegate->committee->display_name}}</strong>。</p>
                    <p>如果该代表不慎填写了错误的委员会，或者该代表所选委员会超员需要调整，您可以在此变更该代表的委员会。</p>
                    <button name="doLock" type="button" class="btn btn-info" onclick="$('#ot_changecmt').hide(); $('#ot_changecmt_confirm').show();">开始</button>
                </div>
                <div id="ot_changecmt_confirm" style="display: none;">
                    <form action="{{mp_url('/ot/changeCommittee')}}" method="post" id="changeCommitteeForm" data-validate="parsley">
                    {{csrf_field()}}
                    <input type="hidden" name="reg_id" value="{{$reg->id}}">
                    <h3 class="m-t-sm">变更委员会</h3>

                    <p>请选择您希望将{{$reg->user->name}}变更的目标委员会。</p>

                <div class="form-group">
                  <select id="" name="committee" class="form-control" data-required="true">
                    <option value="" selected="">请选择</option>';
                @foreach (Reg::currentConference()->committees as $committee)
                    <option value="{{$committee->id}}">{{$committee->display_name}}</option>
                @endforeach
                </select></div>
                   <button name="submit" type="submit" class="btn btn-success" onclick="loader(this)">变更委员会</button>
                   <button name="cancel" type="button" class="btn btn-link" onclick="$('#doAssign').hide(); $('#interviewOpSelect').show();">取消</button>
                   </form>

                </div>
<script>
$('#changeCommitteeForm').submit(function(e){
    e.preventDefault();
    $.post('{{mp_url('/ot/changeCommittee')}}', $('#changeCommitteeForm').serialize()).done(function(data) {
        $.snackbar({content: data});
        $("#ajaxModal").load("{{mp_url('/ot/regInfo.modal/'.$reg->id.'?active=operations')}}");
    });
});
</script>
