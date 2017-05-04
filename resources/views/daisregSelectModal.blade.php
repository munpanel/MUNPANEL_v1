<div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-4 b-r">
              <center>
              <a href="{{ mp_url('/reg2.modal/dais') }}" data-toggle="ajaxModal" class="btn btn-link"{{!$daisUse ? ' disabled=""' : ''}}>
              <i class="fa fa-graduation-cap" aria-hidden="true" style="font-size:7em"></i><br/>
              学术团队申请
              <br><small><i>{{$daisMsg or '点击开始'}}</i></small>
              </a>
              </center>
              </div>
              <div class="col-sm-4 b-r">
              <center>
              <a href="{{ mp_url('/reg2.modal/ot') }}" data-toggle="ajaxModal" class="btn btn-link"{{!$otUse ? ' disabled=""' : ''}}>
              <i class="fa fa-flag" aria-hidden="true" style="font-size:7em"></i><br/>
              会务团队申请
              <br><small><i>{{$otMsg or '点击开始'}}</i></small>
              </a>
              </center>
              </div>
            </div>
          </div>          
      </div><!-- /.modal-content -->
</div>
