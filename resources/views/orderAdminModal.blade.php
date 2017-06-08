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
                            <td width="35%">支付时间</td>
                            <td width="65%">{{$order->payed_at}}</td>
                        </tr>
                        <tr>
                            <td width="35%">发货时间</td>
                            <td width="65%">{{$order->shipped_at}}</td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
          <div class="modal-body">
      </div><!-- /.modal-content -->
</div>
