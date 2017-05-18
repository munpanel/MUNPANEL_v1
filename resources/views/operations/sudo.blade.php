                <div id="ot_sudo" style="display: block;">
                    <h3 class="m-t-sm">SUDO 模式</h3>

                    <p>SUDO 模式可以让您以该报名身份访问本场会议系统，请谨慎使用，避免造成不必要的麻烦。</p>
                    <button name="sudo" type="button" class="btn btn-primary" onclick="$('#ot_sudo').hide(); $('#ot_sudo_confirm').show();">进入 sudo 模式</button>

                </div>
                <div id="ot_sudo_confirm" style="display: none;">
                    <h3 class="m-t-sm">危险！</h3>

                    <p>您即将以该报名身份访问本场会议系统，请慎重考虑，谨慎操作！进入后切换身份即可退出sudo模式</p>
                    <p>您确实要继续吗？</p>

                   <a class="btn btn-danger" href="{{mp_url('/doSwitchIdentity/'.$reg->id)}}" onclick="loader(this)">是</a>
                   <button name="cancel" type="button" class="btn btn-white" onclick="$('#ot_sudo_confirm').hide(); $('#ot_sudo').show();">否</button>


                </div>
