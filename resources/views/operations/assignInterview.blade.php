                <div id="pre_select" style="display: block;">
                    <h3 class="m-t-sm">安排面试</h3>

                    {{-- <p><span class="label label-warning">注意</span> 这是二次面试分配。</p> --}}
                    <p>此代表已经通过审核，将需要为其安排面试。</p>
                    <p>点击<strong>分配面试</strong>按钮后，将会出现可选择的面试官列表，您可以分配一位面试官面试此代表。</p>
                    <p>如果此代表具有规定的免试资格，可以以免试通过方式完成此代表的面试流程。点击<strong>免试通过</strong>按钮后，将会出现可选择的面试官列表，您需要分配一位面试官为此代表分配席位。</p>

                    {{-- <p><span class="label label-warning">注意</span> 这位代表的面试安排曾被join("、", $rollback_data); 回退，请在笔记中了解回退原因。</p>--}}

                    <button name="" type="button" class="btn btn-info" onclick="$('#do_assign').show(); $('#pre_select').hide();">分配面试</button>
                    <button name="" type="button" class="btn btn-info" onclick="$('#do_exempt').show(); $('#pre_select').hide();">免试通过</button>

                </div>

                <div id="do_assign" style="display: none;">
                    <h3 class="m-t-sm">分配面试官</h3>

                    {{-- <p><span class="label label-warning">注意</span> 这是二次面试分配。</p>--}}

                    <p>请在此列表中选择面试官，面试官姓名右侧显示了面试官当前分配的未完成面试数量。</p>

                    <form action="{{mp_url('/ot/assignInterview/'.$reg->id)}}" method="post">
                    {{csrf_field()}}

                          <div class="m-b">
                            <select style="width:260px" class="interviewer-list" name="interviewer">
                                @foreach (\App\Interviewer::list() as $name => $group)
                                <optgroup label="{{$name}}">
                                    @foreach ($group as $iid => $iname)
                                    <option value="{{$iid}}">{{$iname}}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                          </div>

                        <p>分配完成之后，MUNPANEL 将自动通知代表和面试官。</p>

                   <button name="submit" type="submit" class="btn btn-info">分配面试官</button>
                   <button name="cancel" type="button" class="btn btn-link" onclick="$('#do_assign').hide(); $('#pre_select').show();">取消</button>

                   </form>

                </div>

                <div id="do_exempt" style="display: none;">
                    <h3 class="m-t-sm">免试指派席位</h3>

                    <p>将会以免试通过方式完成此代表的面试流程，请在此列表中选择面试官，选定的面试官将可以直接为此代表分配席位。</p>
