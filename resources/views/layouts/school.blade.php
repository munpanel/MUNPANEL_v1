              <li class="@yield('home_active')">
                <a href="{{ secure_url('/home') }}">
                  <i class="fa fa-home"></i>
                  <span>Home</span>
                </a>
              </li>
              <li class="@yield('regManage_active')">
                <a href="{{ secure_url('/regManage') }}">
                  <i class="fa fa-tasks"></i>
                  <span>Registration Management</span>
                </a>
              </li>
