@php
$mppay = Reg::currentConferenceID() != 2;
@endphp
<div class="modal-dialog">
      <div class="modal-content">
<header class="header bg-dark bg-gradient">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#choose" class="pay-tabs" data-toggle="tab" aria-expanded="true">请选择支付方式</a></li>
            <!--li><a href="#" class="pay-tabs tee-tabs" data-toggle="tab" channel="alipay" aria-expanded="false">支付宝</a></li>
            <li><a href="#" class="pay-tabs tee-tabs" data-toggle="tab" channel="wxpay" aria-expanded="false">微信支付</a></li>
            <li><a href="#jdpay" class="pay-tabs" data-toggle="tab" aria-expanded="false">京东钱包</a></li-->
            @if ($mppay)
            <li><a href="#mppay" class="pay-tabs" data-toggle="tab"  aria-expanded="false">MUNPANEL Pay (BETA)</a></li>
            @endif
            @php
            $i = 0;
            @endphp
            @foreach($custom as $method)
            <li><a href="#customMethod_{{$i++}}" class="pay-tabs" data-toggle="tab"  aria-expanded="false">{{$method['name']}}</a></li>
            @endforeach
          </ul>
        </header>
      <div class="tab-content">
        <section class="tab-pane active" id="choose">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
                <div class="alert alert-info"><b>请选择支付方式。{{$mppay ? '推荐使用 MUNPANEL Pay 线上缴费渠道，目前支持支付宝。付款完成后，系统可自动确认缴费状态。':''}}</b></div>
              </div>
            </div>
          </div>          
        </section> 
        <!--section class="tab-pane" id="pay">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
                <center><div class="alert alert-info"><b>请等待下方二维码加载成功后扫描付款</b></div>
                <div id="native"></div></center>
              </div>
            </div>
          </div>          
        </section>
        <section class="tab-pane" id="jdpay">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
                <center><div class="alert alert-info"><b>请在手机端打开此页面并点击以下按钮</b></div>
                <button id="jdPayButton" class="btn btn-sm btn-success">点此支付（手机）</button>
                </center>
              </div>
            </div>
          </div>          
        </section-->
        <section class="tab-pane" id="mppay">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
                <div class="alert alert-info"><b>使用此通道线上缴费，系统自动确认缴费状态。</b></div>
                <div>
                <button id="mpPayButton" class="btn btn-sm btn-success">点此付款</button>
                </div>
              </div>
            </div>
          </div>          
        </section>
        @php
        $i = 0;
        @endphp
        @foreach($custom as $method)
        <section class="tab-pane" id="customMethod_{{$i++}}">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
                <div class="alert alert-info"><b>使用此种方式需要组织团队人工确认缴费状态。</b></div>
                <div>
                <p>
                {!!textWithBr($method['description'])!!}
                </p>
                </div>
              </div>
            </div>
          </div>
        </section>
        @endforeach
      </div><!-- /.modal-content -->
</div>
{{--
<script>
var TEE_API_URL= "{{Config::get('teegon.api_url')}}";
var client_id = "{{Config::get('teegon.client_id')}}";
</script>
<script src="{{Config::get('teegon.site_url')}}jslib/t-charging.min.js"></script>
--}}
<script>
    function checkStatus() {
       $.ajax({  
        type:"GET",  
        url:"{{mp_url('/ajax/payWait/'.$id)}}",  
        timeout:60000,
        success:function(data,textStatus){  
            if (data == 'success') {
                console.log('Thanks for paying lol');
                $('#ajaxModal').modal('hide');
                $('#ajaxModal').remove();
                var $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"><div class="row"><div class="col-sm-12 b-r"><div class="alert alert-success"><b>您已付款成功！</b></div></div></div></div></div></div></div></div>');
                $('body').append($modal);
                $modal.modal();
                setTimeout(function(){location.reload();}, 1000);
            }
            else {
                console.log('No payment yet - AJAX return: '+data);
                checkStatus();
            }
        },  
        error:function(XMLHttpRequest,textStatus,errorThrown){  
          if(textStatus=="timeout"){  
              chekStatus();
          }  
        }  
       });  
    }
    {{--
    $('.tee-tabs').click(function(e) {
        $('#native').empty();
        $('.tab-pane').toggleClass('active', false);
        $('#pay').toggleClass('active', true);
        $.ajax({
            url: "{{route('payInfo')}}",
            data: "_token={{ csrf_token() }}&oid={{$id}}&channel="+$(e.target).attr('channel'),
            method:'post'
        }).done(tee.charge);
        if (typeof payajax_start !== undefined)
        {
            payajax_start = true;
            checkStatus();
        }
    });
    $('#jdPayButton').click(function(e) {
        $.ajax({
            url: "{{route('payInfo')}}",
            data: "_token={{ csrf_token() }}&oid={{$id}}&channel=jdh5_pinganpay",
            method:'post'
        }).done(tee.charge);
        if (typeof payajax_start !== undefined)
        {
            payajax_start = true;
            checkStatus();
        }
    });
    --}}
    $('#mpPayButton').click(function(e) {
        $.ajax({
            url: "{{route('mpPayInfo')}}",
            data: "_token={{ csrf_token() }}&oid={{$id}}",
            method:'post'
        }).done(function(data) {
            window.location = data;
        });
        if (typeof payajax_start !== undefined)
        {
            payajax_start = true;
            checkStatus();
        }
    });
</script>
