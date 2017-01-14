              <li class="@yield('home_active')">
                <a href="{{ secure_url('/home') }}">
                  <i class="fa fa-home"></i>
                  <span>Home</span>
                </a>
              </li>
              <li class="@yield('invoice_active')">
                <a href="{{ secure_url('/pay/invoice') }}">
                  <i class="fa fa-money"></i>
                  <span>Invoice</span>
                </a>
              </li>
              <li class="dropdown-submenu @yield('assignments_active')">
                <a href="{{ secure_url('/assignments') }}" class="dropdown-toggle" > <!-- data-toggle="dropdown"-->
                  <i class="fa fa-flask"></i>
                  <span>Assignments</span>
                </a>
                <!--ul class="dropdown-menu">
                  <li>
                    <a href="assign_detail.html">
                      Academic Test
                    </a>
                  </li>
                  <li>
                    <a href="assign_detail.html">
                      <b class="badge pull-right">FB</b>Position Paper
                    </a>
                  </li>
                  <li>
                    <a href="assign_detail.html">
                      <b class="badge bg-danger pull-right">ND</b>Topic Division
                    </a>
                  </li>
                </ul-->
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
                  <span>Live Screen</span>
                </a>
              </li>
              <li class="@yield('shop_active')">
                <a href="{{ secure_url('/store') }}">
                  <i class="fa fa-shopping-bag"></i>
                  <span>Souvenir Store</span>
                </a>
              </li>
              <li class="@yield('fb_active')">
                <a href="{{ secure_url('/fb') }}">
                  <i class="fa fa-pencil"></i>
                  <span>Feedback</span>
                </a>
              </li>
              <li class="@yield('ddltimer_active')">
                <a href="{{ secure_url('/ddltimer') }}">
                  <i class="fa fa-clock-o"></i>
                  <span>Deadline</span>
                </a>
              </li>
