              <li class="@yield('home_active')">
                <a href="{{ mp_url('/home') }}">
                  <i class="fa fa-home"></i>
                  <span>Home</span>
                </a>
              </li>
              @if (Reg::current()->dais->status == 'success')              
              @foreach(Auth::user()->regs->where('conference_id', Reg::currentConferenceID())->where('enabled', true) as $reg)
              @if ($reg->type == 'interviewer')
              <li class="@yield('interview_active')">
                <a href="{{ mp_url('/doSwitchIdentity/'.$reg->id.'?target=/interviews') }}">
                  <i class="fa fa-comments"></i>
                  <span>Interviews</span>
                </a>
              </li>
              @permission('view-regs')
              {{-- 用于面试协理给代表分配面试官 --}}
              <li class="@yield('regManage_active')">
                <a href="{{ mp_url('/regManage') }}">
                  <i class="fa fa-tasks"></i>
                  <span>Arrangement</span>
                </a>
              </li>
              @endpermission
              @endif
              @endforeach
              <li class="@yield('roles_active')">
                <a href="{{ mp_url('/roleAlloc') }}">
                  <i class="fa fa-wheelchair"></i>
                  <span>Role Allocation</span>
                </a>
              </li>
              <li class="dropdown-submenu @yield('assignments_active')">
                <a href="{{ mp_url('/assignments') }}" class="dropdown-toggle" > <!-- data-toggle="dropdown"-->
                  <i class="fa fa-flask"></i>
                  <span>Assignments &amp; Handins</span>
                </a>
              </li>
              <li class="@yield('documents_active')">
                <a href="{{ mp_url('/documents') }}">
                  <i class="fa fa-file-text"></i>
                  <span>Documents</span>
                </a>
              </li>
              <li class="@yield('pages_active')">
                <a href="{{ mp_url('/pages') }}">
                  <!--b class="badge bg-info pull-right">3</b-->
                  <i class="fa fa-envelope-o"></i>
                  <span>E-Pages</span>
                </a>
              </li>
              <li class="@yield('chair_active')">
                <a href="{{ mp_url('/chair') }}">
                  <i class="fa fa-tasks"></i>
                  <span>Console</span>
                </a>
              </li>
              @permission('view-regs')
              {{--那么这个人就有个ot身份--}}
              @endpermission
              @endif
              <!--li class="@yield('store_active')">
                <a href="{{ mp_url('/store') }}">
                  <i class="fa fa-shopping-bag"></i>
                  <span>Souvenir Store</span>
                </a>
              </li-->
