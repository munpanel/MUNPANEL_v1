@extends('layouts.app')
@section('regManage_active', 'active')
@section('content')
<section class="vbox">
            <header class="header bg-white b-b clearfix">
              <div class="row m-t-sm">
                <div class="col-sm-6 m-b-xs">
                  <!--a href="#subNav" data-toggle="class:hide" class="btn btn-sm btn-info"><i class="fa fa-caret-right text fa fa-large"></i><i class="fa fa-caret-left text-active fa fa-large"></i></a>
                  <a href="#" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Create</a-->
                </div>
                <div class="col-sm-6 m-b-xs">
                  <div class="input-group">
                    <input type="text" class="input-sm form-control" placeholder="Search">
                    <span class="input-group-btn">
                      <button class="btn btn-sm btn-white" type="button">Go!</button>
                    </span>
                  </div>
                </div>
              </div>
            </header>
            <section class="scrollable wrapper w-f">
              <section class="panel">
                <div class="table-responsive">
                  <table class="table table-striped m-b-none">
                    <thead>
                      <tr>
                        <th width="20"><input type="checkbox"></th>
                        <th width="20"></th>
                        <!--th class="th-sortable" data-toggle="class">Project
                          <span class="th-sort">
                            <i class="fa fa-sort-down text"></i>
                            <i class="fa fa-sort-up text-active"></i>
                            <i class="fa fa-sort"></i>
                          </span>
                        </th-->
                        <th>姓名</th>
                        <th>委员会</th>
                        <th>搭档</th>
                        <th width="30"></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($delegates as $delegate)
                      <tr>
                        <td><input type="checkbox" name="post[]" value="2"></td>
                        <td><a href="{{ secure_url('/reg.modal/' . $delegate->user->id) }}" data-toggle="ajaxModal"><i class="fa fa-search-plus"></i></a></td>
                        <td>{{$delegate->user->name}}</td>
                        <td>{{$delegate->committee->name}}</td>
                        <td>{{$delegate->partnername}}</td>
                        <td>
                          @if ($delegate->status == 'reg')
                            <a href="#" class="approval-status" data-id="{{$delegate->user->id}}"><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i></a>
                          @else
                            <a href="#" class="active approval-status" data-id="{{$delegate->user->id}}"><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i></a>
                          @endif
                        </td>
                      </tr>
                      @endforeach
                      @foreach ($volunteers as $volunteer)
                      <tr>
                        <td><input type="checkbox" name="post[]" value="2"></td>
                        <td><a href="{{ secure_url('/reg.modal/' . $volunteer->user->id) }}" data-toggle="ajaxModal"><i class="fa fa-search-plus"></i></a></td>
                        <td>{{$volunteer->user->name}}</td>
                        <td>志愿者</td>
                        <td>无</td>
                        <td>
                          @if ($volunteer->status == 'reg')
                            <a href="#" class="approval-status" data-id="{{$volunteer->user->id}}"><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i></a>
                          @else
                            <a href="#" class="active approval-status" data-id="{{$volunteer->user->id}}"><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i></a>
                          @endif
                        </td>
                      </tr>
                      @endforeach
                      @foreach ($observers as $observer)
                      <tr>
                        <td><input type="checkbox" name="post[]" value="2"></td>
                        <td><a href="{{ secure_url('/reg.modal/' . $observer->user->id) }}" data-toggle="ajaxModal"><i class="fa fa-search-plus"></i></a></td>
                        <td>{{$observer->user->name}}</td>
                        <td>观察员</td>
                        <td>无</td>
                        <td>
                          @if ($observer->status == 'reg')
                            <a href="#" class="approval-status" data-id="{{$observer->user->id}}"><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i></a>
                          @else
                            <a href="#" class="active approval-status" data-id="{{$observer->user->id}}"><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i></a>
                          @endif
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </section>
            </section>
            <!--footer class="footer bg-white b-t">
              <div class="row m-t-sm text-center-xs">
                <div class="col-sm-4">
                  <select class="input-sm form-control input-s-sm inline">
                    <option value="0">Bulk action</option>
                    <option value="1">Delete selected</option>
                    <option value="2">Bulk edit</option>
                    <option value="3">Export</option>
                  </select>
                  <button class="btn btn-sm btn-white">Apply</button>                  
                </div>
                <div class="col-sm-4 text-center">
                  <small class="text-muted inline m-t-sm m-b-sm">showing 20-30 of 50 items</small>
                </div>
                <div class="col-sm-4 text-right text-center-xs">                
                  <ul class="pagination pagination-sm m-t-none m-b-none">
                    <li><a href="#"><i class="fa fa-chevron-left"></i></a></li>
                    <li><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li><a href="#"><i class="fa fa-chevron-right"></i></a></li>
                  </ul>
                </div>
              </div>
            </footer-->
          </section>
@endsection
