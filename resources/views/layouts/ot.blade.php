              <li class="@yield('home_active')">
                <a href="{{ secure_url('/home') }}">
                  <i class="fa fa-home"></i>
                  <span>Home</span>
                </a>
              </li>
              <li class="@yield('committeeManage_active')">
                <a href="{{ secure_url('/committeeManage') }}">
                  <i class="fa fa-sitemap"></i>
                  <span>Committees Management</span>
                </a>
              </li>
              <li class="@yield('userManage_active')">
                <a href="{{ secure_url('/userManage') }}">
                  <i class="fa fa-users"></i>
                  <span>Users Management</span>
                </a>
              </li>
              <li class="@yield('schoolManage_active')">
                <a href="{{ secure_url('/schoolManage') }}">
                  <i class="fa fa-university"></i>
                  <span>Schools Management</span>
                </a>
              </li>
              <li class="@yield('regManage_active')">
                <a href="{{ secure_url('/regManage') }}">
                  <i class="fa fa-tasks"></i>
                  <span>Registration Management</span>
                </a>
              </li>
