<div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-body">
            <div class="row">
                <div class="col-sm-12 b-r">
                    <form role="form" action="{{secure_url('/changePwd')}}" method="post"  data-validate="parsley" id="changePwdForm">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label>原密码</label>
                            <input type="password" name="oldPassword" id="oldPassword" class="form-control" data-required="true">
                        </div>
                        <div class="form-group">
                            <label>新密码</label>
                            <input type="password" name="newPassword" id="newPassword" class="form-control" data-required="true">
                        </div>
                        <div class="form-group">
                            <label>确认密码</label>
                            <input type="password" class="form-control" data-required="true" data-equalto="#newPassword">
                        </div>
                        <div class="checkbox m-t-lg">
                            <button type="submit" class="btn btn-sm btn-success pull-right text-uc m-t-n-xs"><strong>修改</strong></button>
                        </div> 
                      </form>
                </div>
            </div>
          <div class="modal-body">
      </div><!-- /.modal-content -->
</div>
<script>
$('#changePwdForm').parsley();
</script>
