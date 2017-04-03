              <li class="@yield('home_active')">
                <a href="{{ mp_url('/home') }}">
                  <i class="fa fa-home"></i>
                  <span>Home</span>
                </a>
              </li>
              <li class="@yield('interview_active')">
                <a href="{{ mp_url('/interviews') }}">
                  <i class="fa fa-comments"></i>
                  <span>Interviews</span>
                </a>
              </li>
              @foreach(Auth::user()->regs->where('conference_id', Reg::currentConferenceID())->where('enabled', true) as $reg)
              @if ($reg->type == 'ot' || $reg->type == 'dais')
              <li>
                <a href="{{ mp_url('/doSwitchIdentity/'.$reg->id) }}">
                  <i class="fa fa-sign-out"></i>
                  <span>返回 {{$reg->regText()}} 身份</span>
                </a>
              </li>
              @endif
              @endforeach
