                <div id="ot_verify" style="display: block;">
                    <h3 class="m-t-sm">设定代表组</h3>
                    {{-- <p><span class="label label-warning">注意</span> 这是二次面试分配。</p> --}}
                    <form action="post">
                        <p>请选中以下复选框的项目以变更{{$reg->user->name}}归属的代表组。</p>
                        <input type="hidden" name="id" value="{{$reg->id}}">
                        {{-- TODO: 插入多选框 这里还是组建一个带复选框的 table 吧 --}}
                        <a class="btn btn-success" href="{{mp_url('/ot/oVerify/'.$reg->id)}}">保存更改</a>
                        <button name="" type="button" class="btn btn-danger m-l-sm" onclick="">重置</button>
                    </form>
                </div>
