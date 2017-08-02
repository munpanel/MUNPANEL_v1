                <div id="ot_changepair" style="display: block;">
                    <h3 class="m-t-sm">修改配对</h3>
                    @if (is_object($reg->delegate->partner))
                    <p>该代表已配对搭档<strong>{{$reg->delegate->partner->reg->user->name}}</strong>。</p>
                    @endif
                    @if (is_object($reg->roommate))
                    <p>该代表已配对室友<strong>{{$reg->roommate->name}}</strong>。</p>
                    @endif
                    <button name="doLock" type="button" class="btn btn-info" onclick="$('#ot_changepair').hide(); $('#ot_changepair_confirm').show();">开始</button>
                </div>
                <div id="ot_changepair_confirm" style="display: none;">
                    <form action="{{mp_url('/ot/changePairing')}}" method="post" id="changePairingForm" data-validate="parsley">
                    {{csrf_field()}}
                    <input type="hidden" name="reg_id" value="{{$reg->id}}">
                    <h3 class="m-t-sm">变更配对</h3>
                    对方报名ID
                    <input type="text" name="other_id" value="">
                    <select name="type">
                    <option value="partner">partner</option>
                    <option value="roommate">roommate</option>
                    </select>

                   <button name="submit" type="submit" class="btn btn-success" onclick="loader(this)">变更配对</button>
                   <button name="cancel" type="button" class="btn btn-link" onclick="$('#doAssign').hide(); $('#interviewOpSelect').show();">取消</button>
                   </form>

                </div>
<script>
$('#changePairingForm').submit(function(e){
    e.preventDefault();
    $.post('{{mp_url('/ot/changePairing')}}', $('#changePairingForm').serialize()).done(function(data) {
        $.snackbar({content: data});
        $("#ajaxModal").load("{{mp_url('/ot/regInfo.modal/'.$reg->id.'?active=operations')}}");
    });
});
</script>
