@extends('layouts.app')
@section('document_active', 'active')
@push('scripts')
    <script src="{{secure_url('/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{secure_url('/js/documentsList.js')}}"></script>
@endpush
@push('css')
    <link rel="stylesheet" href="{{secure_url('/css/jquery.dataTables.min.css')}}" type="text/css" />
@endpush
@section('content')
<section class="vbox">
  <header class="header b-b bg-white">          
      <p>您的学术文件列表</p>
  </header>
  <section class="scrollable wrapper w-f">
    <section class="panel">
      <div class="table-responsive">
        <table class="table table-striped m-b-none" id="document-table">
          <thead>
            <tr>
              <th width="20"></th>
              <th width="20">#</th>
              <th width="55%">学术文件标题</th>
              <th>发布日期</th>
              <!--th width="30"></th-->
            </tr>
          </thead>
        </table>
      </div>
    </section>
  </section>
  <footer class="footer bg-white b-t">
    <div class="row m-t-sm text-center-xs">
      <div class="col-sm-12">
        <small class="text-muted inline m-t-sm m-b-sm" id="document-pageinfo"></small>
      </div>
    </div>
  </footer>
</section>
@endsection
