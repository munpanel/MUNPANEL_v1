<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-body">
      <div class="row">
        <div class="col-sm-12 b-r">
          <form method="post" action="{{mp_url('/setAccomodate')}}" >
            {{csrf_field()}}
            <input type="hidden" name="reg_id" value="{{Reg::currentID()}}">
            @if (Reg::currentConferenceID() == 3)
            <p>由于我们的疏忽，我们在报名表单中遗失了"是否住宿"的选项，现需要您向报名表单补充相关内容。对您带来的不便我们深表歉意。</p>
            @else
            <p>亲爱的{{Reg::current()->name()}}，我们的住宿预订即将开始。目前正在统计住宿意向，请在下方选择您是否希望住宿。如您希望和朋友安排在同一间房，也可以在下方填写您的意向室友，我们将会优先为您与您的朋友安排到一间房；如您没有意向室友，我们将会为您随机安排室友。经过协商，友谊宾馆的标间协议价为410元／间／天（即205元/人/天），出于经济、安全与方便考虑，如无特殊情况，我们建议所有代表都选择北京友谊宾馆（即本次会议举办地点）入住，如有特殊情况欢迎与保障团队成员联系。</p>
            <p><b>此处仅统计住宿意向，对于具体入住时间、住宿特殊需求等，我们将于近日公布相应表单，敬请期待。</b></p>
            <p>在您填完此意向且席位得到锁定后，系统将自动为您生成会费订单。再次感谢您对环梦的关注与支持，预祝开会愉快</p>
            @endif
            {!!$confForm!!}
            <button type="submit" class="btn btn-info">提交住宿信息</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
