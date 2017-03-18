<div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-4 b-r">
              <center>
              <a href="{{ mp_url('/reg2.modal/delegate') }}" data-toggle="ajaxModal" class="btn btn-link"{{!$delegateUse ? ' disabled=""' : ''}}>
              <i class="fa fa-globe" aria-hidden="true" style="font-size:7em"></i><br/>
              代表报名
              <br><small><i>{{$delegateMsg or '点击开始'}}</i></small>
              </a>
              </center>
              </div>
              <div class="col-sm-4 b-r">
              <center>
              <a href="{{ mp_url('/reg2.modal/volunteer') }}" data-toggle="ajaxModal" class="btn btn-link"{{!$volunteerUse ? ' disabled=""' : ''}}>
              <i class="fa fa-handshake-o" aria-hidden="true" style="font-size:7em"></i><br/>
              志愿者报名
              <br><small><i>{{$volunteerMsg or '点击开始'}}</i></small>
              </a>
              </center>
              </div>
              <div class="col-sm-4 b-r">
              <center>
              <a href="{{ mp_url('/reg2.modal/observer') }}" data-toggle="ajaxModal" class="btn btn-link"{{!$observerUse ? ' disabled=""' : ''}}>
              <i class="fa fa-eye" aria-hidden="true" style="font-size:7em"></i><br/>
              观察员报名
              <br><small><i>{{$observerMsg or '点击开始'}}</i></small>
              </a>
              </center>
              </div>
            </div>
          </div>          
      </div><!-- /.modal-content -->
</div>
