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
                <div class="alert alert-info"><b>请选择支付方式。目前支持微信、支付宝。</b></div>
              </div>
            </div>
          </div>          
        </section> 
        <section class="tab-pane" id="pay">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
                <center><div class="alert alert-info"><b>请等待下方二维码加载成功后扫描付款</b></div>
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
            url: "{{route('payInfo')}}",
            data: "_token={{ csrf_token() }}&oid={{$id}}&channel="+$(e.target).attr('channel')+"&return="+window.location,
            method:'post'
        }).done(tee.charge);


    });
</script>
