              <li class="@yield('home_active')">
                <a href="{{ secure_url('/home') }}">
                  <i class="fa fa-home"></i>
                  <span>Home</span>
                </a>
              </li>
              <li class="@yield('roles_active')">
                <a href="{{ secure_url('/roleAlloc') }}">
                  <i class="fa fa-wheelchair"></i>
                  <span>Role Allocation</span>
                </a>
              </li>
              <li class="dropdown-submenu @yield('assignments_active')">
                <a href="{{ secure_url('/assignmentManage') }}" class="dropdown-toggle" > <!-- data-toggle="dropdown"-->
                  <i class="fa fa-flask"></i>
                  <span>Assignments & Handins</span>
                </a>
              </li>
              <li class="@yield('documents_active')">
                <a href="{{ secure_url('/documents') }}">
                  <i class="fa fa-file-text"></i>
                  <span>Documents</span>
                </a>
              </li>
              <li class="@yield('pages_active')">
                <a href="{{ secure_url('/pages') }}">
                  <!--b class="badge bg-info pull-right">3</b-->
                  <i class="fa fa-envelope-o"></i>
                  <span>E-Pages</span>
                </a>
              </li>
              <li class="@yield('chair_active')">
                <a href="{{ secure_url('/chair') }}">
                  <i class="fa fa-tasks"></i>
                  <span>Console</span>
                </a>
              </li>
              <li class="@yield('shop_active')">
                <a href="{{ secure_url('/shop') }}">
                  <i class="fa fa-shopping-bag"></i>
                  <span>Souvenir Shop</span>
                </a>
              </li>