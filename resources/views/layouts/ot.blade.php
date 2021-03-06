              <li class="@yield('home_active')">
                <a href="{{ mp_url('/home') }}">
                  <i class="fa fa-home"></i>
                  <span>Home</span>
                </a>
              </li>
              @permission('view-all-interviews')
              <li class="@yield('interview_active')">
                <a href="{{ mp_url('/interviews'.(Reg::current()->can('view-all-interviews') ? '/-1' : '')) }}">
                  <i class="fa fa-comments"></i>
                  <span>Interviews</span>
                </a>
              </li>
              @endpermission
              @permission('assign-roles')
              <li class="@yield('roles_active')">
                <a href="{{ mp_url('/roleAlloc') }}">
                  <i class="fa fa-wheelchair"></i>
                  <span>Role Allocation</span>
                </a>
              </li>
              @endpermission
              @permission('edit-committees')
              <li class="@yield('committeeManage_active')">
                <a href="{{ mp_url('/committeeManage') }}">
                  <i class="fa fa-sitemap"></i>
                  <span>Committees Management</span>
                </a>
              </li>
              @endpermission
              {{--
              @permission('edit-users')
              <li class="@yield('userManage_active')">
                <a href="{{ mp_url('/userManage') }}">
                  <i class="fa fa-users"></i>
                  <span>Users Management</span>
                </a>
              </li>
              @endpermission
              This should be separated to a global admin instead of ot of a conf.
              As people may sudo into global admin's ot identity.
              --}}
              @permission('edit-schools')
              <!--li class="@yield('schoolManage_active')">
                <a href="{{ mp_url('/schoolManage') }}">
                  <i class="fa fa-university"></i>
                  <span>Schools Management</span>
                </a>
              </li-->
              @endpermission
              @permission('view-regs')
              <li class="@yield('regManage_active')">
                <a href="{{ mp_url('/regManage') }}">
                  <i class="fa fa-tasks"></i>
                  <span>Registration Management</span>
                </a>
              </li>
              @endpermission
              @permission('edit-orders')
              <li class="@yield('orderManage_active')">
                <a href="{{ mp_url('/store/orders/-1') }}">
                  <i class="fa fa-money"></i>
                  <span>Orders Audition</span>
                </a>
              </li>
              @endpermission
              @permission('edit-ot')
              <li class="@yield('teamManage_active')">
                <a href="{{ mp_url('/teamManage') }}">
                  <i class="fa fa-flag"></i>
                  <span>Team Management</span>
                </a>
              </li>
              @endpermission
              @permission('edit-ot')
              <li class="@yield('confConfig_active')">
                <a href="{{ mp_url('/confConfig') }}">
                  <i class="fa fa-cogs"></i>
                  <span>Conference Settings</span>
                </a>
              </li>
              @endpermission
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
