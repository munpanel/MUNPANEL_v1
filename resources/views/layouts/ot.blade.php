              <li class="@yield('home_active')">
                <a href="{{ secure_url('/home') }}">
                  <i class="fa fa-eye"></i>
                  <span>Home</span>
                </a>
              </li>
              <li class="@yield('userManage_active')">
                <a href="{{ secure_url('/userManage') }}">
                  <i class="fa fa-users"></i>
                  <span>Users Management</span>
                </a>
              </li>
              <li class="@yield('regManage_active')">
                <a href="{{ secure_url('/regManage') }}">
                  <i class="fa fa-tasks"></i>
                  <span>Registration Management</span>
                </a>
              </li>
