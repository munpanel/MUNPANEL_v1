                <div id="ot_seatlock" style="display: block;">
                    <h3 class="m-t-sm">代表席位锁定</h3>
                    <p>该代表已从{{$reg->delegate->assignedNations->count()}}个备选席位中选择了<strong>{{$reg->delegate->nation->name}}</strong>。</p>
                    <p>锁定席位后，该代表席位将不能再改变；同时，我们会将该席位从其他人的备选列表中去除。</p>
                    @if ($reg->delegate->nation->committee_id != $reg->delegate->nation->committee_id)
                    <p>锁定席位后，我们将把该代表的委员会信息从&nbsp;<b>{{$reg->delegate->committee->display_name}}</b>&nbsp;更新为&nbsp;<b>{{$reg->delegate->nation->committee->display_name}}</b>&nbsp;</p>
                    @endif
                    <button name="doLock" type="button" class="btn btn-primary" onclick="$('#ot_seatlock').hide(); $('#ot_seatlock_confirm').show();">锁定</button>

                </div>
                <div id="ot_seatlock_confirm" style="display: none;">
                    <h3 class="m-t-sm">代表席位锁定</h3>

                    <p>您确实要继续吗？</p>

                   <a class="btn btn-danger" onclick="loader(this); jQuery.get('{{mp_url('/ot/seatLock/'.$reg->id)}}', function(){$('#ajaxModal').load('{{mp_url('/ot/regInfo.modal/'.$reg->id.'?active=operations')}}');});">是</a>
                   <button name="cancel" type="button" class="btn btn-white" onclick="$('#ot_seatlock_confirm').hide(); $('#ot_seatlock').show();">否</button>


                </div>
