                <div id="ot_seatlock" style="display: block;">
                    <h3 class="m-t-sm">代表席位解锁</h3>
                    <p>该代表的席位已锁定为<strong>{{$reg->delegate->nation->name}}</strong>。</p>
                    <p>您可以点击以下按钮解锁该代表当前的席位。<br>该解锁席位功能仅将该代表的席位分配退回至未锁定状态，并不会自动改变或移除席位；席位解锁后约 72 小时内无任何操作将重新锁定当前席位。</p>
                    <button name="doLock" type="button" class="btn btn-primary" onclick="$('#ot_seatlock').hide(); $('#ot_seatlock_confirm').show();">解锁</button>

                </div>
                <div id="ot_seatlock_confirm" style="display: none;">
                    <h3 class="m-t-sm">代表席位解锁</h3>

                    <p>您确实要继续吗？</p>

                   <a class="btn btn-danger" onclick="loader(this); jQuery.get('{{mp_url('/ot/seatUnLock/'.$reg->id)}}', function(){$('#ajaxModal').load('{{mp_url('/ot/regInfo.modal/'.$reg->id.'?active=operations')}}');});">是</a>
                   <button name="cancel" type="button" class="btn btn-white" onclick="$('#ot_seatlock_confirm').hide(); $('#ot_seatlock').show();">否</button>


                </div>
