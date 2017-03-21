                <div id="" style="display: block;">
                    <h3>报名信息审核</h3>

                    {{-- <p><span class="label label-warning">注意</span> 这是二次面试分配。</p> --}}
                    <p>此代表报名参加本次会议，请在“信息”页确认此代表的报名信息。</p>
                    <p>点击<strong>审核通过</strong>按钮后，您或组委其他成员将可以对此代表进行后续操作。</p>

                    {{-- <p><span class="label label-warning">注意</span> 这位代表的面试安排曾被join("、", $rollback_data); 回退，请在笔记中了解回退原因。</p>--}}
                    
                    <a class="btn btn-success" href="{{mp_url('/ot/oVerify/'.$reg->id)}}">审核通过</a>

                </div>
