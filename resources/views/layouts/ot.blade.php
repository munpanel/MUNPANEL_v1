              <li class="@yield('home_active')">
                <a href="{{ mp_url('/home') }}">
                  <i class="fa fa-home"></i>
                  <span>Home</span>
                </a>
              </li>              
              @if (Reg::current()->type == 'dais' && Reg::current()->dais->status == 'success')
              <li class="@yield('roles_active')">
                <a href="{{ mp_url('/roleAlloc') }}">
                  <i class="fa fa-wheelchair"></i>
                  <span>Role Allocation</span>
                </a>
              </li>
              <li class="dropdown-submenu @yield('assignments_active')">
                <a href="{{ mp_url('/assignments') }}" class="dropdown-toggle" > <!-- data-toggle="dropdown"-->
                  <i class="fa fa-flask"></i>
                  <span>Assignments & Handins</span>
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
              @endif
              @permission('edit-committees')
              <li class="@yield('committeeManage_active')">
                <a href="{{ mp_url('/committeeManage') }}">
                  <i class="fa fa-sitemap"></i>
                  <span>Committees Management</span>
                </a>
              </li>
              @endpermission
              @permission('edit-users')
              <li class="@yield('userManage_active')">
                <a href="{{ mp_url('/userManage') }}">
                  <i class="fa fa-users"></i>
                  <span>Users Management</span>
                </a>
              </li>
              @endpermission
              @permission('edit-schools')
              <li class="@yield('schoolManage_active')">
                <a href="{{ mp_url('/schoolManage') }}">
                  <i class="fa fa-university"></i>
                  <span>Schools Management</span>
                </a>
              </li>
              @endpermission
              @permission('view-regs')
              <li class="@yield('regManage_active')">
                <a href="{{ mp_url('/regManage') }}">
                  <i class="fa fa-tasks"></i>
                  <span>Registration Management</span>
                </a>
              </li>
              @endpermission
              {{--@permission('view-regs')--}}
              <li class="@yield('teamManage_active')">
                <a href="{{ mp_url('/teamManage') }}">
                  <i class="fa fa-flag"></i>
                  <span>Team Management</span>
                </a>
              </li>
              {{--@endpermission--}}
              {{--@permission('view-regs')--}}
              <li class="@yield('confConfig_active')">
                <a href="{{ mp_url('/confConfig') }}">
                  <i class="fa fa-cogs"></i>
                  <span>Conference Settings</span>
                </a>
              </li>
              {{--@endpermission--}}
              @permission('edit-nations')
              <li class="@yield('nationManage_active')">
                <a href="{{ mp_url('/nationManage') }}">
                  <i class="fa fa-id-badge"></i>
                  <span>Nation Management</span>
                </a>
              </li>
              @endpermission
              <!--li class="@yield('store_active')">
                <a href="{{ mp_url('/store') }}">
                  <i class="fa fa-shopping-bag"></i>
                  <span>Souvenir Store</span>
                </a>
              </li-->
