<div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
              <form role="form"  data-validate="parsley" action="{{ secure_url('/verifyTel') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                  <label>您收到的验证码</label>
                  <input type="text" class="form-control" id="code" name="code">
                </div>
                <button type="submit" class="btn btn-sm btn-success pull-right text-uc m-t-n-xs"><strong>验证</strong></button>
              </form>
              </div>
            </div>
          </div>          
      </div><!-- /.modal-content -->
</div>
