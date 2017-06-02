                <div id="ot_verify" style="display: block;">
                    <h3 class="m-t-sm">报名信息审核</h3>

                    <p>{{$reg->user->name}}{{$reg->type == 'dais' ? '申请成为本次会议学术团队成员' : ($reg->type == 'ot' ? '申请成为本次会议会务团队成员' : '报名参加本次会议')}}，请在“信息”页确认此代表的报名信息。</p>
                    <p>点击<strong>通过审核</strong>按钮后，您或组织团队其他成员将可以对此代表进行后续操作。</p>
                    @if ($reg->type == 'delegate' && $reg->delegate->hasRegAssignment() > 0)
                    <p><span class="label label-warning">注意</span> 这位代表仍有 {{$reg->delegate->hasRegAssignment()}} 项早期学术作业未完成。</p>
                    @endif
                    <a class="btn btn-success" onclick="loader(this); jQuery.get('{{mp_url('/ot/oVerify/'.$reg->id)}}', function(){$('#ajaxModal').load('{{mp_url('/ot/regInfo.modal/'.$reg->id.'?active=operations')}}');});">通过审核</a>
                    <button name="noVerify" type="button" class="btn btn-danger" onclick="$('#ot_verify').hide(); $('#ot_noverify_confirm').show();">不通过</button>

                </div>
                <div id="ot_noverify_confirm" style="display: none;">
                    <h3 class="m-t-sm">危险！</h3>

                    <p>您将不通过此参会者的{{in_array($reg->type, ['dais', 'ot']) ? '申请' : '报名'}}</p>
                    <p>您确实要继续吗？</p>

                   <a class="btn btn-danger" onclick="loader(this); jQuery.get('{{mp_url('/ot/oNoVerify/'.$reg->id)}}', function(){$('#ajaxModal').load('{{mp_url('/ot/regInfo.modal/'.$reg->id.'?active=operations')}}');});">是</a>
                   <button name="cancel" type="button" class="btn btn-white" onclick="$('#ot_noverify_confirm').hide(); $('#ot_verify').show();">否</button>


                </div>
