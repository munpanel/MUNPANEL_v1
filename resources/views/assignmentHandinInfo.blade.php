@extends('layouts.app')
@section('assignment_active', 'active')
@push('scripts')
    <script src="{{secure_url('/js/fuelux/fuelux.js')}}"></script>
    <script src="{{secure_url('/js/file-input/bootstrap.file-input.js')}}"></script>
    <script>
    $(document).ready(function() {
        $('#uploadWizard')
            // Call the wizard plugin
            .wizard()

            // Triggered when clicking the Complete button
            .on('finished.fu.wizard', function(e) {
            $('#uploadForm').submit();
        });
    });
    </script>
@endpush
@push('css')
    <link rel="stylesheet" href="{{secure_url('/js/fuelux/fuelux.css')}}" type="text/css" />
@endpush
@section('content')
      <section class="vbox">
        <header class="header bg-white b-b">
          <p>{{$assignment->title}}</p>
        </header>
        <section class="scrollable wrapper">
          <div class="row">
            <div class="col-lg-6">
              <!-- .accordion -->
              <div class="panel-group m-b" id="accordion2">
                <div class="panel">
                  <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                      作业概况
                    </a>
                  </div>
                  <div id="collapseOne" class="panel-collapse in">
                    <div class="panel-body text-sm">
                      <b>作业标题: </b>{{$assignment->title}}<br><b>提交对象: </b>{{$assignment->scope()}} (以
@if ($assignment->subject_type == 'individual')
  个人
@elseif ($assignment->subject_type == 'nation')
  国家
@else
  搭档
@endif
为单位)<br><b>提交形式: </b>{{$assignment->handin_type == 'upload' ? '文件上传' : '在线文本编辑器'}}<br><b>提交期限: </b>{{$assignment->deadline}}<br><b>最近一次提交: </b>{{$handin->user->name}}提交于{{$handin->updated_at}}
                    </div>
                  </div>
                </div>
                <div class="panel">
                  <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                      作业信息
                    </a>
                  </div>
                  <div id="collapseTwo" class="panel-collapse collapse">
                    <div class="panel-body text-sm">
                      {!!$assignment->description!!}
                    </div>
                  </div>
                </div>
                <div class="panel">
                  <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
                      主席反馈
                    </a>
                  </div>
                  <div id="collapseThree" class="panel-collapse collapse">
                    <div class="panel-body text-sm">
                      Sorry, it's not available.
                    </div>
                  </div>
                </div>
              </div>
              </div>
              <div class="col-lg-6">
               <section class="panel bg-info lter no-borders">
                <div class="panel-body">
                  <span class="h4">{{$assignment->title}}</span>
                  @if (strtotime(date("y-m-d H:i:s")) < strtotime($assignment->deadline))
                      <a class="badge bg-primary pull-right" href="{{secure_url('/assignment/'.$assignment->id.'/resubmit')}}">重新提交</a>
                  @endif
                  <div class="text-center padder m-t">
                    <i class="fa fa-file-text fa fa-4x"></i>
                  </div>
                </div>
                <footer class="panel-footer lt">
                  <center><b><a href="{{secure_url('/assignment/'.$assignment->id.'/download')}}">点此下载</a></b></center>
                </footer>
              </section>
          </div>
        </section>
      </section>
      <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
    </section>
    <!-- /.vbox -->

@endsection
