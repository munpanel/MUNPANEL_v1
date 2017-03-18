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
                      <b>作业标题: </b>{{$assignment->title}}<br><b>提交对象: </b>{{$assignment->scope()}} (以{{$assignment->subject_type == 'individual' ? '个人' : ($assignment->subject_type == 'nation' ? '国家' : '搭档')}}为单位)<br><b>提交形式: </b>{{$assignment->handin_type == 'upload' ? '文件上传' : ($assignment->handin_type == 'form' ? '在线填写表单' : '在线文本编辑器')}}<br><b>提交期限: </b>{{$assignment->deadline}}
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
              <!-- / .accordion -->
            </div>
          <div class="col-lg-6">
               <section class="panel bg-info lter no-borders">
                <div class="panel-body">
                  <span class="h4">{{$assignment->title}}</span>
                  <div class="text-center padder m-t">
                    <i class="fa fa-file-text fa fa-4x"></i>
                  </div>
                </div>
                <footer class="panel-footer lt">
              @if (strtotime(date("y-m-d H:i:s")) < strtotime($assignment->deadline))
                @if ($assignment->handin_type == 'upload')
              <section class="panel wizard" id="uploadWizard"> 
                <div class="clearfix wizard-steps">
                  <ul class="steps">
                    <li data-target="#step1" class="active"><span class="badge badge-info">1</span>选择文件</li>
                    <li data-target="#step2"><span class="badge">2</span>补充说明</li>
                    <li data-target="#step3"><span class="badge">3</span>确认</li>
                  </ul>
                  <div class="actions">
                    <button type="button" class="btn btn-white btn-xs btn-prev" disabled="disabled">Prev</button>
                    <button type="button" class="btn btn-white btn-xs btn-next" data-last="Finish" data-validate="parsley">Next</button>
                  </div>
                </div>
                <form id="uploadForm" enctype="multipart/form-data" action="{{mp_url('/assignment/'.$assignment->id.'/upload')}}"  method="post">
                {{ csrf_field() }}
                <div class="step-content">
                  <div class="step-pane active" id="step1" style="color:#000000">请选择您要上传的学术作业文件。<br><input type="file" title="Browse" name="file" class="btn btn-sm btn-info file-input parsley-required" data-required="true"></div>
                  <div class="step-pane" id="step2" style="color:#000000">如果您对上交的作业有任何附加说明，请在此填写：<br><textarea name="remark" rows="3" style="width:100%;"></textarea></div>
                  <div class="step-pane" id="step3" style="color:#000000">您确定要提交吗？</div>
                </div>
                </form>
              </section>
                @else
                  <center><b><a href="{{mp_url('/assignment/'.$assignment->id.'/form')}}">获取题目并开始</a></b></center>
                @endif
              @else
              截止时间已到，您并未上传。
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
