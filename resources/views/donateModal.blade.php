<div class="modal-dialog">
      <div class="modal-content">
<header class="header bg-dark bg-gradient">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#choose" data-toggle="tab" aria-expanded="true">请选择支付方式</a></li>
            <li><a href="#" class="pay" data-toggle="tab" channel="alipay" aria-expanded="false">支付宝</a></li>
            <li><a href="#" class="pay" data-toggle="tab" channel="wxpay" aria-expanded="false">微信支付</a></li>
          </ul>
        </header>
      <div class="tab-content">
        <section class="tab-pane active" id="choose">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
                <div class="alert alert-info"><b>感谢您对MUNPANEL的关注与支持。MUNPANEL的发展离不开您们的大力支持。请在下方填写电子邮箱及捐助金额。一次性捐助5元以上可获得开发周报邮件订阅。选择支付方式。目前支持微信、支付宝。</b></div>
                  <div class="form-group">
                    <label>您的email</label>
                    <input type="text" id="donaterEmail" class="form-control">
                  </div>
                  <div class="form-group">
                    <label>捐赠金额</label>
                    <input type="text" id="amount" class="form-control" placeholder="5.00">
                  </div>
              </div>
            </div>
          </div>          
        </section> 
        <section class="tab-pane" id="pay">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
                <center><div class="alert alert-info"><b>请等待下方二维码加载成功后扫描付款，付款成功后请注意查收邮件</b></div>
                <div id="native"></div></center>
              </div>
            </div>
          </div>          
        </section>
      </div><!-- /.modal-content -->
</div>
<script>
var TEE_API_URL= "{{Config::get('teegon.api_url')}}";
var client_id = "{{Config::get('teegon.client_id')}}";
</script>
<script src="{{Config::get('teegon.site_url')}}jslib/t-charging.min.js"></script>
<script src="{{Config::get('teegon.site_url')}}static/js/jquery.min.js"></script>
<script>
    $('.pay').click(function(e) {
        $('#native').empty();
        $('#choose').toggleClass('active', false);
        $('#pay').toggleClass('active', true);
        $.ajax({
            url: "{{secure_url('/pay/info')}}",
            data: "_token={{ csrf_token() }}&channel="+$(e.target).attr('channel')+"&return="+window.location,
            method:'post'
        }).done(tee.charge);


    });
</script>
