<div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-body">
            <div class="row">
                <div class="col-sm-12 b-r">
                <table class="table table-bordered table-striped" style="clear: both">
                    <tbody>
                        <tr>
                            <td width="35%">订单编号</td>
                            <td width="65%">{{$order->id}}</td>
                        </tr>
                        <tr>
                            <td width="35%">订单状态</td>
                            <td width="65%">{{$order->status}}</td>
                        </tr>
                        <tr>
                            <td width="35%">发货方式</td>
                            <td width="65%">{{$order->shipment_method}}</td>
                        </tr>
                        <tr>
                            <td width="35%">地址</td>
                            <td width="65%">{{$order->address}}</td>
                        </tr>
                        <tr>
                            <td width="35%">支付渠道</td>
                            <td width="65%">{{$order->payment_channel}}</td>
                        </tr>
                        <tr>
                            <td width="35%">交易币种</td>
                            <td width="65%">CNY</td>
                        </tr>
                        <tr>
                            <td width="35%">交易金额</td>
                            <td width="65%">{{number_format($order->price, 2)}}</td>
                        </tr>
                        <tr>
                            <td width="35%">支付流水号</td>
                            <td width="65%">{{$order->charge_id}}</td>
                        </tr>
                        <tr>
                            <td width="35%">支付方信息</td>
                            <td width="65%">{{$order->buyer}}</td>
                        </tr>
                        <tr>
                            <td width="35%">第三方交易单号</td>
                            <td width="65%">{{$order->payment_no}}</td>
                        </tr>
                        <tr>
                            <td width="35%">下单时间</td>
                            <td width="65%">{{$order->created_at}}</td>
                        </tr>
                        <tr>
                            <td width="35%">支付时间</td>
                            <td width="65%">{{$order->payed_at}}</td>
                        </tr>
                        <tr>
                            <td width="35%">发货时间</td>
                            <td width="65%">{{$order->shipped_at}}</td>
                        </tr>
                    </tbody>
                </table>
                @if ($order->status == 'unpaid')
                <script>
function doManualPay() {
    $.post('{{mp_url('/store/manualPay/'.$order->id)}}', $('#manualPayForm').serialize()).done(function(data) {
        $.snackbar({content: data});
        @unless ($refresh == 'no')
        if (data == 'success') {
            setTimeout(function(){
                    location.reload();
            }, 500);
        }
        @endunless
    });
}
                </script>
                <hr>
                <h3>手动确认付款</h3>
                      <p>此方法用于系统不支持自动确认的自定义缴费渠道交易确认或特殊情况（如活动免单、现金交易等）的交易确认。请谨慎操作。以下表单信息用于存档与日后对账，为避免不必要的麻烦，请<b>务必认真填写</b>。</p>
                      <form class="form-horizontal" id="manualPayForm">
                        {{csrf_field()}}
                        <div class="form-group">
                          <label class="col-lg-2 control-label">支付渠道</label>
                          <div class="col-lg-10">
                            <input type="text" name="payment_channel" class="form-control" placeholder="Payment Channel">
                            <span class="help-block m-b-none">例如：银行转帐</span>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-lg-2 control-label">交易单号</label>
                          <div class="col-lg-10">
                            <input type="text" name="payment_no" class="form-control" placeholder="Payment No.">
                            <span class="help-block m-b-none">第三方交易单号，如银行流水号等</span>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-lg-2 control-label">支付方信息</label>
                          <div class="col-lg-10">
                            <input type="text" name="buyer" class="form-control" placeholder="Payer Info">
                            <span class="help-block m-b-none">付款人信息，如卡号</span>
                          </div>
                        </div>
                        <div class="form-group" id="confirm" style="display: block;">
                          <div class="col-lg-offset-2 col-lg-10">
                            <a href="#" class="btn btn-sm btn-default" onclick="$('#confirm').hide(); $('#doConfirm').show();">确认付款</a>
                          </div>
                        </div>
                        <div class="form-group" id="doConfirm" style="display: none;">
                          <div class="col-lg-offset-2 col-lg-10">
                            <p>您确认该订单已被缴费么？</p>
                            <a href="#" class="btn btn-sm btn-default" onclick="$('#confirm').show(); $('#doConfirm').hide();">我再想想</a>
                            <a href="#" class="btn btn-sm btn-danger" onclick="loader(this);doManualPay();">确认</a>
                          </div>
                        </div>
                      </form>
                @endif
                </div>
            </div>
          <div class="modal-body">
      </div><!-- /.modal-content -->
</div>
