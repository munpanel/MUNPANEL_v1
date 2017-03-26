@extends('layouts.app')
@section('assignments_active', 'active')
@push('scripts')
    <script src="{{mp_url('/js/fuelux/fuelux.js')}}"></script>
    <script src="{{mp_url('/js/file-input/bootstrap.file-input.js')}}"></script>
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
    <link rel="stylesheet" href="{{mp_url('/js/fuelux/fuelux.css')}}" type="text/css" />
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
                      作业概况
                  </div>
                    <div class="panel-body text-sm">
                      <b>作业标题: </b>{{$assignment->title}}<br><b>提交对象: </b>{{$assignment->scope()}} (以{{$assignment->subject_type == 'individual' ? '个人' : ($assignment->subject_type == 'nation' ? '国家' : '搭档')}}为单位)<br><b>提交形式: </b>{{$assignment->handin_type == 'upload' ? '文件上传' : ($assignment->handin_type == 'form' ? '在线填写表单' : '在线文本编辑器')}}<br><b>提交期限: </b>{{$assignment->deadline}}<br><b>最近一次提交: </b>{{$handin->reg->user->name}}提交于{{nicetime($handin->updated_at)}}
                    </div>
                </div>
                <div class="panel">
                  <div class="panel-heading">
                      作业信息
                  </div>
                    <div class="panel-body text-sm">
                      {!!$assignment->description!!}
                    </div>
                </div>
                <div class="panel">
                  <div class="panel-heading">
                      主席反馈
                  </div>
                    <div class="panel-body text-sm">
                      Sorry, it's not available.
                    </div>
                </div>
              </div>
              </div>
              <div class="col-lg-6">
               <section class="panel bg-info lter no-borders">
                <div class="panel-body">
                  <span class="h4">{{$assignment->title}}</span>
                  @if (strtotime(date("y-m-d H:i:s")) < strtotime($assignment->deadline) && $assignment->handin_type != 'form')
                      <a class="badge bg-primary pull-right" href="{{mp_url('/assignment/'.$assignment->id.'/resubmit')}}">重新提交</a>
                  @endif
                  <div class="text-center padder m-t">
                    <i class="fa fa-file-text fa fa-4x"></i>
                  </div>
                </div>
                <footer class="panel-footer lt">
                  @if ($assignment->handin_type == 'upload')
                  <center><b><a href="{{mp_url('/assignment/'.$assignment->id.'/download')}}">点此下载</a></b></center>
                  @else
                  @include("components.formAnswer")
                  @endif
                </footer>
              </section>
          </div>
        </section>
      </section>
      <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
    </section>
    <!-- /.vbox -->

@endsection
