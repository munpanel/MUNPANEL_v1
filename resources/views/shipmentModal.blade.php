<div class="modal-dialog">
      <div class="modal-content">
<header class="header bg-dark bg-gradient">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#choose" data-toggle="tab" aria-expanded="true">请选择送货方式</a></li>
            <li><a href="#conf" data-toggle="tab" aria-expanded="false">会上自提</a></li>
            <li><a href="#mail" data-toggle="tab" aria-expanded="false">快递到家</a></li>
          </ul>
        </header>
      <div class="tab-content">
        <section class="tab-pane active" id="choose">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
                <div class="alert alert-info"><b>请选择送货方式，我们支持会上自提（无需运费）和快递到家（需要运费）。</b></div>
              </div>
            </div>
          </div>          
        </section> 
        <section class="tab-pane" id="conf">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
                <form role="form" id="delform" data-validate="parsley" action="{{ mp_url('/store/doCheckout') }}" method="post">
                  <div class="alert alert-success"><b>推荐此种方式：</b>会议中途在指定时间凭代表牌领取所预定的纪念品。</div>
                  {{ csrf_field() }}
                  <input type="hidden" name="method" value="conference">
                  <button type="submit" class="btn btn-success pull-right text-uc m-t-n-xs">提交并支付订单</button>
                </form>
              </div>
            </div>
          </div>          
        </section>
        <section class="tab-pane" id="mail">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
                <form role="form" id="delform" data-validate="parsley" action="{{ mp_url('/store/doCheckout') }}" method="post">
                  <div class="alert alert-warning">您需额外支付15元运费。</div>
                  {{ csrf_field() }}
                  <input type="hidden" name="method" value="mail">
                  <div class="form-group">
                    <label>地址</label>
                    <input type="text" class="form-control"  name="address" data-required="true">
                    <span class="help-block m-b-none">请输入完整正确地址（含邮编）</span>
                  </div>
                  <button type="submit" class="btn btn-success pull-right text-uc m-t-n-xs">提交并支付订单</button>
                </form>
              </div>
            </div>
          </div>          
        </section>
      </div><!-- /.modal-content -->
</div>
