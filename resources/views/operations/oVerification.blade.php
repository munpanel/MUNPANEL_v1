                <div id="ot_verify" style="display: block;">
                    <h3>报名信息审核</h3>

                    {{-- <p><span class="label label-warning">注意</span> 这是二次面试分配。</p> --}}
                    <p>此代表报名参加本次会议，请在“信息”页确认此代表的报名信息。</p>
                    <p>点击<strong>通过审核</strong>按钮后，您或组委其他成员将可以对此代表进行后续操作。</p>
                    @if (Reg::current()->type == 'delegate' && Reg::current()->delegate->hasRegAssignment() > 0)
                    <p><span class="label label-warning">注意</span> 这位代表仍有 {{Reg::current()->delegate->hasRegAssignment()}} 项早期学术作业未完成。</p>
                    @endif
                    <a class="btn btn-success" href="{{mp_url('/ot/oVerify/'.$reg->id)}}">通过审核</a>
                    <button name="noVerify" type="button" class="btn btn-danger m-l-sm" onclick="$('#ot_verify').hide(); $('#ot_noverify_confirm').show();">不通过</button>

                </div>
                <div id="ot_noverify_confirm" style="display: none;">
                    <h3>危险！</h3>

                    {{-- <p><span class="label label-warning">注意</span> 这是二次面试分配。</p>--}}

                    <p>如果您不通过此参会者的报名，其所有报名信息都将被删除！</p>
                    <p>您确实要继续吗？</p>

                   <a class="btn btn-danger" href="{{mp_url('/ot/oNoVerify/'.$reg->id)}}">是</a>
                   <button name="cancel" type="button" class="btn btn-white m-l-sm" onclick="$('#ot_noverify_confirm').hide(); $('#ot_verify').show();">否</button>


                </div>
