<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
          <center>
            <h4>{{$good->name}}</h4>
            <p>{{'¥' . number_format($good->price, 2)}}
            </p>
            <img src="{{mp_url('/store/goodimg/' . $good->id)}}" style="width: 75%"> {{-- class="shop-image-small"> --}}
          </center>
        </div>
    </div>
</div>
