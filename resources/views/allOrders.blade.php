      <section class="vbox bg-white">
        <header class="header b-b hidden-print">
          <p>{{$user->name}}的所有待发货订单</p>
        </header>
        <section class="scrollable wrapper">
          <!--i class="fa fa-apple fa fa-3x"></i-->       
          <div class="well m-t">
            <div class="row">
              <div class="col-xs-12">
                <strong>付款人:</strong>
                <h4>{{$user->name}}</h4>
                <p>
                  @if (is_object($user->specific()) && is_object($user->specific()->school))
                  {{$user->specific()->school->name}}<br>
                  @endif
                  @if (is_object($user->specific()) && isset($user->specific()->tel))
                  Phone: {{$user->specific()->tel}}<br>
                  @endif
                  Email: {{$user->email}}<br>
                </p>
              </div>
            </div>
          </div>
          @foreach($orders as $order)
          <div class="line"></div>
          <a href="{{secure_url('/shipOrder/'.$order->id)}}" class="btn btn-sm btn-info pull-right">发货</a>
          <h4>订单编号 {{$order->id}}</h4>
          <p class="m-t m-b">
          @if ($order->shipment_method == 'mail')
          快递至: {{$order->address}}<br>
          @elseif ($order->shipment_method == 'conference')
          会议期间取货<br>
          @endif
          {{--
          @if ($order->status == 'cancelled')
              订单状态: <span class="label bg-danger">已取消</span><br>
          @elseif ($order->status == 'unpaid')
              订单状态: <span class="label bg-danger">未支付</span><br>
          @elseif ($order->status == 'paid')
              订单状态: <span class="label bg-info">待发货</span><br>
          @else
              订单状态: <span class="label bg-success">已发货</span><br>
          @endif
          --}}
              付款时间：{{isset($order->payed_at)?$order->payed_at:'未付款'}}<br>
              {{-- 发货时间：{{isset($order->shipped_at)?$order->shipped_at:'未发货'}} --}}
          </p>
          <?php $orderItems = $order->items(); ?>
          <table class="table">
            <thead>
              <tr>
                <th>描述</th>
                <th width="90">单价</th>
                <th width="60">数量</th>
                <th width="90">总价</th>
              </tr>
            </thead>
            <tbody>
              @foreach($orderItems as $item)
              <tr>
                <td>{{$item['name']}}</td>
                <td>¥{{number_format($item['price'], 2)}}</td>
                <td>{{$item['qty']}}</td>
                <td>¥{{number_format($item['qty'] * $item['price'], 2)}}</td>
              </tr>
              @endforeach
              <tr>
                <td colspan="3" class="text-right no-border"><strong>总计</strong></td>
                <td><strong>¥{{number_format($order->price, 2)}}</strong></td>
              </tr>
            </tbody>
          </table>
          @endforeach
          <!--账单状态如有问题，请微信联系adamyi。-->
        </section>
      </section>
      <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
    </section>
