                <div id="school_verify" style="display: block;">
                    <h3 class="m-t-sm">报名信息审核</h3>

                    <p>{{$reg->user->name}}报名参加本次会议，请在“信息”页确认此代表的报名信息。</p>
                    <p>点击<strong>通过审核</strong>按钮后，该报名将进入“等待组织团队审核”环节。</p>
                    <a class="btn btn-success" onclick="loader(this); jQuery.get('{{mp_url('/school/sVerify/'.$reg->id)}}', function(){$('#ajaxModal').load('{{mp_url('/school/regInfo.modal/'.$reg->id.'?active=operations')}}');});">通过审核</a>
                    <button name="nsVerify" type="button" class="btn btn-danger" onclick="$('#school_verify').hide(); $('#school_noverify_confirm').show();">不通过</button>

                </div>
                <div id="school_noverify_confirm" style="display: none;">
                    <h3 class="m-t-sm">危险！</h3>

                    <p>您将不通过此参会者的报名</p>
                    <p>您确实要继续吗？</p>

                   <a class="btn btn-danger" onclick="loader(this); jQuery.get('{{mp_url('/school/sNoVerify/'.$reg->id)}}', function(){$('#ajaxModal').load('{{mp_url('/school/regInfo.modal/'.$reg->id.'?active=operations')}}');});">是</a>
                   <button name="cancel" type="button" class="btn btn-white" onclick="$('#school_noverify_confirm').hide(); $('#school_verify').show();">否</button>


                </div>
