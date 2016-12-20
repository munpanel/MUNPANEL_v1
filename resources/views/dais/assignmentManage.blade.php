@extends('layouts.app')
@section('assignment_active', 'active')
@section('content')
    <!-- TODO: 检查 /public/js/dais.assignmentManage.js -->
      <header class="header b-b bg-white">      
          <a href="{{secure_url('/ot/committeeDetails.modal/new')}}" class="btn btn-sm btn-success details-modal pull-right"><i class="fa fa-plus"></i> 新建</a>
          <p>BJMUNC2017 {{$committee->display_name}} 学术作业清单</p>
        </header>
      <section class="scrollable wrapper w-f">
      <section class="panel">
                <div class="table-responsive">
                  <table class="table table-striped m-b-none" id="assignment-table">
                    <thead>
                      <tr>
                        <th width="30">#</th>
                        <th width="40">操作</th>
                        <th>学术作业标题</th>
                        <th>提交期限</th>
                        <th width="30"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>1</td>
                        <td><a href="assign_detail2.html"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a href="assign_detail.html"><i class="fa fa-folder-open"></i></a></td>
                        <td>Academic Test</td>
                        <td>Feb 1, 2016</td>
                        <td>
                          <a class="active" href="#" data-toggle="class"><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i></a>
                        </td>
                      </tr>
                        <tr><td>2</td>
                        <td><a href="assign_detail2.html"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a href="assign_detail.html"><i class="fa fa-folder-open"></i></a></td>
                        <td>Position Paper<b class="badge pull-right">New Feedback</b></td>
                        <td>Feb 10, 2016</td>
                        <td>
                          <a class="active" href="#" data-toggle="class"><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i></a>
                        </td>
                      </tr>
                        <tr><td>3</td>
                        <td><a href="assign_detail2.html"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a href="assign_detail.html"><i class="fa fa-folder-open"></i></a></td>
                        <td>Topic Division</td>
                        <td>Feb 10, 2016</td>
                        <td>
                          <a href="#" data-toggle="class"><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i></a>
                        </td>
                      </tr>
                      <tr><td style="text-align:center;padding:20px;border-bottom:none;" colspan="5"><div class="progress progress-striped active" style="width:50%;margin:auto;"><div class="progress-bar progress-bar-info" style="width:66%;" data-original-title="66%" data-toggle="tooltip"></div></div></td></tr>
                    </tbody>
                  </table>
                </div>
              </section>
      </section>
@endsection